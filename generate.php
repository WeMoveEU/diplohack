<?php
include ("variables.php");
include ("functions_sparql.php");

$unanimity = "SELECT DISTINCT ?act ?id ?title ?actDate ?name ?propName ?vote from <http://data.consilium.europa.eu/id/dataset/votingresults> where {?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/votingrule> <http://data.consilium.europa.eu/data/public_voting/consilium/votingrule/unanimity> . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/act> ?act . ?act skos:prefLabel ?id . ?act skos:definition ?title . ?observation ?what ?value . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/actdate> ?actDate . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/measureproperty/vote> ?voteIRI . ?voteIRI skos:prefLabel ?vote . ?what a <http://purl.org/linked-data/cube%23DimensionProperty> . ?value skos:prefLabel ?name . ?what <http://purl.org/dc/terms/identifier> ?propName}";

$everyvote = "SELECT DISTINCT ?act ?id ?title ?actDate ?name ?propName ?vote from <http://data.consilium.europa.eu/id/dataset/votingresults> where {?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/act> ?act . ?act skos:prefLabel ?id . ?act skos:definition ?title . ?observation ?what ?value . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/actdate> ?actDate . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/measureproperty/vote> ?voteIRI . ?voteIRI skos:prefLabel ?vote . ?what a <http://purl.org/linked-data/cube%23DimensionProperty> . ?value skos:prefLabel ?name . ?what <http://purl.org/dc/terms/identifier> ?propName}";

$agriculture = "SELECT DISTINCT ?act ?id ?title ?actDate ?name ?propName ?vote from <http://data.consilium.europa.eu/id/dataset/votingresults> where {?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/policyarea> <http://data.consilium.europa.eu/data/public_voting/consilium/policyarea/agriculture> . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/act> ?act . ?act skos:prefLabel ?id . ?act skos:definition ?title . ?observation ?what ?value . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/actdate> ?actDate . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/measureproperty/vote> ?voteIRI . ?voteIRI skos:prefLabel ?vote . ?what a <http://purl.org/linked-data/cube%23DimensionProperty> . ?value skos:prefLabel ?name . ?what <http://purl.org/dc/terms/identifier> ?propName}";

$r = processQuery($everyvote);
//$r = processQuery($agriculture);

$fp = fopen('data/votes.csv', 'w');
$iso = ["AT","BE","BG","CY","CZ","DE","DK","EE","GR","ES","FI","FR","HU","IE","IT","LT","LU","LV","MT","NL","PL","PT","RO","SE","SI","SK","GB","HR"];
//$t = $r['3243-3'];

$nbvotes = 0;
foreach ($r as $t) {
  $t->date=substr($t->actDate,0,10);
  $bl =array("votes_FOR","votes_AGAINST","votes_ABSTAIN","votes_DIDNTVOTE","actDate");
  foreach ($bl as $i)
    unset($t->$i);
  $vote = (array) $t;
  
/*  $vote = array(
      "id"=>$t->id,
      "date"=>substr($t->actDate,0,10),
      "policyarea"=>$t->policyarea,
      "title"=>$t->title,
  );
 */

  $nbcountries=0;
  foreach ($iso as $c) {
    if (array_key_exists($c,$t->countries))
      $vote[$c] = $t->countries[$c];
    else 
      $vote[$c]="";
  }
  unset($vote["countries"]);
  //  ksort($countries);

  if ($nbvotes==0) {
    fputcsv($fp, array_keys($vote));
//    print_r(array_keys($vote)); die ("aaa");
  }
  fputcsv($fp, $vote);
  $nbvotes++;
}

//echo json_encode (array_keys($countries));

//print_r($t);

fclose($fp);

echo "generated $nbvotes\n";

