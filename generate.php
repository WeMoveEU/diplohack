<?php
include ("variables.php");
include ("functions_sparql.php");

$unanimity = "SELECT DISTINCT ?act ?id ?title ?actDate ?name ?propName ?vote from <http://data.consilium.europa.eu/id/dataset/votingresults> where {?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/votingrule> <http://data.consilium.europa.eu/data/public_voting/consilium/votingrule/unanimity> . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/act> ?act . ?act skos:prefLabel ?id . ?act skos:definition ?title . ?observation ?what ?value . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/actdate> ?actDate . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/measureproperty/vote> ?voteIRI . ?voteIRI skos:prefLabel ?vote . ?what a <http://purl.org/linked-data/cube%23DimensionProperty> . ?value skos:prefLabel ?name . ?what <http://purl.org/dc/terms/identifier> ?propName}";

$everyvote = "SELECT DISTINCT ?act ?id ?title ?actDate ?name ?propName ?vote from <http://data.consilium.europa.eu/id/dataset/votingresults> where {?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/act> ?act . ?act skos:prefLabel ?id . ?act skos:definition ?title . ?observation ?what ?value . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/actdate> ?actDate . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/measureproperty/vote> ?voteIRI . ?voteIRI skos:prefLabel ?vote . ?what a <http://purl.org/linked-data/cube%23DimensionProperty> . ?value skos:prefLabel ?name . ?what <http://purl.org/dc/terms/identifier> ?propName}";

$agriculture = "SELECT DISTINCT ?act ?id ?title ?actDate ?name ?propName ?vote from <http://data.consilium.europa.eu/id/dataset/votingresults> where {?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/policyarea> <http://data.consilium.europa.eu/data/public_voting/consilium/policyarea/agriculture> . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/act> ?act . ?act skos:prefLabel ?id . ?act skos:definition ?title . ?observation ?what ?value . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/dimensionproperty/actdate> ?actDate . ?observation <http://data.consilium.europa.eu/data/public_voting/qb/measureproperty/vote> ?voteIRI . ?voteIRI skos:prefLabel ?vote . ?what a <http://purl.org/linked-data/cube%23DimensionProperty> . ?value skos:prefLabel ?name . ?what <http://purl.org/dc/terms/identifier> ?propName}";

$r = processQuery($everyvote);

$fp = fopen('data/votes.csv', 'w');
$votes=array ("FOR"=>1,"AGAINST"=>-1,"ABSTAIN"=>0 ,"DIDNTVOTE"=> null);
$iso = ["AT","BE","BG","CY","CZ","DE","DK","EE","GR","ES","FI","FR","HU","IE","IT","LT","LU","LV","MT","NL","PL","PT","RO","SE","SI","SK","GB","HR"];
//$t = $r['3243-3'];

$nbvotes = 0;
foreach ($r as $t) {
  $countries=array();
  $vote = array(
      "act"=>$t->act,
      "date"=>substr($t->actDate,0,10),
      "policyarea"=>$t->policyarea,
      "title"=>$t->title,
  );

  foreach ($votes as $k => $v) {
    $i = "votes_".$k;
    $nbcountries=0;
    foreach ($t->$i as $c) {
      $nbcountries++;
      $vote[$c] = $v;
    }
    if ($nbcountries==27){
      $vote["HR"] = "";
    }

  //  ksort($countries);
  }

  if ($nbvotes==0) {
    fputcsv($fp, array_keys($vote));
  }
  fputcsv($fp, $vote);
  $nbvotes++;
}

//echo json_encode (array_keys($countries));

//print_r($t);

fclose($fp);

echo "generated $nbvotes\n";

