<?php


// ***
function querySparqlServerSearch($dateFrom, $dateTo, $area, $conf, $form, $rule, $docNr, $interNr, $string)
{
    // compose SPARQL url
    $sparqlRequest = composeSparqlQuerySearch($dateFrom, $dateTo, $area, $conf, $rule, $docNr, $interNr, $string);

    // get array of Acts
    $actObj_a = array();
    $actObj_a = processQuery($sparqlRequest);

    return $actObj_a;
}


// ***
function querySparqlServerVotes($act_id)
{
    // compose SPARQL url
    $sparqlRequest = composeSparqlQueryVotes($act_id);

    // get array of Acts
    $actObj_a = array();
    $actObj_a = processQuery($sparqlRequest);

    return $actObj_a;
}


// ***
function composeSparqlQuerySearch($dateFrom, $dateTo, $policyArea, $councilConfig, $votingRule, $docNumber, $interNumber, $string)
{
    // http://data.consilium.europa.eu/data/public_voting
    $base_url = $GLOBALS['VOTING_BASE_URL'];


    $sparql_query = htmlspecialchars("SELECT DISTINCT ?act ?id ?title ?actDate ?name ?propName ?vote from <http://data.consilium.europa.eu/id/dataset/votingresults> where {");


    // date FROM
    // date format example: 2016-04-21T22:00:00.000Z
    if( $dateFrom != "")  {
        $date_s = date_create( $dateFrom );
        $dateFromISO = date_format($date_s, "Y-m-d")."T".date_format($date_s, "H:i:s").".000Z";

        $sparql_query = $sparql_query . htmlspecialchars("?observation <".$base_url."/qb/dimensionproperty/actdate> ?actDate .FILTER (?actDate > %22".$dateFromISO."%22^^xsd:dateTime) . ");
    }

    // date TO
    if( $dateTo != "") {
        $date_e = date_create( $dateTo );
        $dateToISO = date_format($date_e, "Y-m-d")."T".date_format($date_e, "H:i:s").".000Z";

        $sparql_query = $sparql_query . htmlspecialchars("?observation <".$base_url."/qb/dimensionproperty/actdate> ?actDate .FILTER (?actDate < %22".$dateToISO."%22^^xsd:dateTime) . ");
    }


    // document number
    if( $docNumber != "") {
        $docNumber_str = str_replace("/", "", $docNumber); // syntax-ready to query

        $sparql_query = $sparql_query . htmlspecialchars("?observation <".$base_url."/qb/dimensionproperty/docnrcouncil> <".$base_url."/consilium/docnrcouncil/".$docNumber_str."> . ");
    }

    // interinstitucional code
    if( $interNumber != "") {
        // syntax-ready to query
        $interNumber_str = str_replace("/", "", $interNumber);
        $interNumber_str = str_replace("(", "", $interNumber_str);
        $interNumber_str = str_replace(")", "", $interNumber_str);
        $interNumber_str = str_replace(" ", "", $interNumber_str);
        $interNumber_str = strtolower($interNumber_str);

        $sparql_query = $sparql_query . htmlspecialchars("?observation <".$base_url."/qb/dimensionproperty/docnrinterinst> <".$base_url."/consilium/docnrinterinst/".$interNumber_str."> . ");
    }


    // policy area
    if( $policyArea != "") {

        $area_name  = $GLOBALS[$policyArea.'_name'];
        $area_value = str_replace(" ", "", strtolower($area_name) ); // lowercase and no spaces

        $sparql_query = $sparql_query . htmlspecialchars("?observation <".$base_url."/qb/dimensionproperty/policyarea> <".$base_url."/consilium/policyarea/".$area_value."> . ");
    }


    // Council configuration
    if( $councilConfig != "") {
        $conf_value  = strtolower( $councilConfig );

        $sparql_query = $sparql_query . htmlspecialchars("?observation <".$base_url."/qb/dimensionproperty/configuration> <".$base_url."/consilium/configuration/".$conf_value."> . ");
    }


    // voting rule    
    if( $votingRule != "")
        $sparql_query = $sparql_query . htmlspecialchars("?observation <".$base_url."/qb/dimensionproperty/votingrule> <".$base_url."/consilium/votingrule/".$votingRule."> . ");


    // string in title
    if( $string != "") 
        $sparql_query = $sparql_query . htmlspecialchars("?act skos:definition ?title . FILTER REGEX (?title, %22".$string."%22) . ");


    // adds string for selecting act properties
    $sparql_query = $sparql_query . htmlspecialchars("?observation <".$base_url."/qb/dimensionproperty/act> ?act . ?act skos:prefLabel ?id . ?act skos:definition ?title . ?observation ?what ?value . ?observation <".$base_url."/qb/dimensionproperty/actdate> ?actDate . ?observation <".$base_url."/qb/measureproperty/vote> ?voteIRI . ?voteIRI skos:prefLabel  ?vote . ?what a <http://purl.org/linked-data/cube%23DimensionProperty> . ?value skos:prefLabel ?name . ?what <http://purl.org/dc/terms/identifier> ?propName}");

    return $sparql_query;
}


// ***
function composeSparqlQueryVotes($act_id)
{
    // http://data.consilium.europa.eu/data/public_voting
    $base_url = $GLOBALS['VOTING_BASE_URL'];

    // make act_id compatible with query syntax
    $act_id_str = str_replace("-", "", $act_id);


    $sparql_query = htmlspecialchars("SELECT DISTINCT ?act ?id ?title ?actDate ?name ?propName ?vote from <http://data.consilium.europa.eu/id/dataset/votingresults> where {");

    // retrieve only act_id
    $sparql_query = $sparql_query . htmlspecialchars("?observation <".$base_url."/qb/dimensionproperty/act> <".$base_url."/consilium/act/".$act_id_str."> . ");

    // adds string for selecting act properties
    $sparql_query = $sparql_query . htmlspecialchars("?observation <".$base_url."/qb/dimensionproperty/act> ?act . ?act skos:prefLabel ?id . ?act skos:definition ?title . ?observation ?what ?value . ?observation <".$base_url."/qb/dimensionproperty/actdate> ?actDate . ?observation <".$base_url."/qb/measureproperty/vote> ?voteIRI . ?voteIRI skos:prefLabel  ?vote . ?what a <http://purl.org/linked-data/cube%23DimensionProperty> . ?value skos:prefLabel ?name . ?what <http://purl.org/dc/terms/identifier> ?propName}");

    return $sparql_query;
}


// ***
function processQuery($sparqlRequest)
{
//echo $sparqlRequest."<br><br>";//mc2

    // send request to the Council server
    $sparqlResponse = issueSparqlQuery($sparqlRequest);

    // convert response to JSON
    $sparqlResponse_JSON = json_decode($sparqlResponse, true);

    // set all bindings from response into an array
    $binding_a = array();
    $binding_a = $sparqlResponse_JSON['results']['bindings'];

    // process all bindings
    $actObj_a  = array();
    $actId_a   = array();

    foreach( $binding_a as $binding ) {

        // id of an Act
        $actId = $binding['id']['value'];

        // check if binding is related to an already processed act
        if( !in_array($actId, $actId_a) ) {

            // this is a NEW act
            array_push($actId_a, $actId);

            $actObj          = new stdClass;

            // create Act object
            $actObj->id      = $actId;
            $actObj->actDate = $binding['actDate']['value'];
            $actObj->title   = $binding['title']['value'];
            $actObj->vote    = $binding['vote']['value'];

            $actObj->votes_FOR       = array();
            $actObj->votes_AGAINST   = array();
            $actObj->votes_ABSTAIN   = array();
            $actObj->votes_DIDNTVOTE = array();

            $actObj_a[$actId] = $actObj;
        }

        // set act final vote
        if( $binding['propName']['value'] == "acttype" )
            $actObj->vote = $binding['vote']['value'];

        // if binding is vote of Country, add vote to the act
        if( $binding['propName']['value'] == "country" ) {

            $countryName = $binding['name']['value'];
            $countryCode = $GLOBALS['EU_STATES_COD'][$countryName];

            if (!property_exists($actObj_a[$actId],"countries") )
                $actObj_a[$actId]->countries = array();
            switch( $binding['vote']['value'] ) {

                case "Voted in favour":
                    $actObj_a[$actId]->countries[$countryCode] = 1;
                    array_push($actObj_a[$actId]->votes_FOR, $countryCode);
                    break;

                case "Voted against":
                    $actObj_a[$actId]->countries[$countryCode] = -1;
                    array_push($actObj_a[$actId]->votes_AGAINST, $countryCode);
                    break;

                case "Abstained":
                    $actObj_a[$actId]->countries[$countryCode] = 0;
                    array_push($actObj_a[$actId]->votes_ABSTAIN, $countryCode);
                    break;

                case "Not participating":
                    $actObj_a[$actId]->countries[$countryCode] = "";
                    array_push($actObj_a[$actId]->votes_DIDNTVOTE, $countryCode);
                    break;
            }
        }
        else {
            // this case sets all other attributes of an act (acttype, votingprocedure, ...)
            $property = $binding['propName']['value'];
            $value    = $binding['name']['value'];

            $actObj_a[$actId]->$property = $value;
        }

    }
//echo count($actObj_a)."<br><br>";//mc2
//var_dump(array_pop($actObj_a));

    return $actObj_a;
}


// ***
function issueSparqlQuery($sparqlQuery)
{
    $baseAPI = "http://data.consilium.europa.eu/sparql?";

    // make URL compatible with SPARQL server
    $sparqlQuery = str_replace(" ",    "%20", $sparqlQuery);
    $sparqlQuery = str_replace("&lt;", "%3C", $sparqlQuery);
    $sparqlQuery = str_replace("&gt;", "%3E", $sparqlQuery);

    $requestUrl = $baseAPI."query=".$sparqlQuery."&format=application%2Fsparql-results%2Bjson";
echo $requestUrl."<br><br>";//mc2
    $response = file_get_contents($requestUrl);

    return $response;
}


?>
