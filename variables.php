<?php

   // starting date of current term
   $STARTING_DATE     = "July 1, 2014";

   // url of Council Open Data server
   $VOTING_BASE_URL = "http://data.consilium.europa.eu/data/public_voting";


   // EP states 
   $EU_STATES_COD = array("GERMANY"  => "DE",
                          "AUSTRIA"  => "AT",
                          "BELGIUM"  => "BE",
                          "BULGARIA" => "BG",
                          "CYPRUS"   => "CY",
                          "DENMARK"  => "DK",
                          "SLOVAKIA" => "SK",
                          "SLOVENIA" => "SI",
                          "SPAIN"    => "ES",
                          "ESTONIA"  => "EE",
                          "FINLAND"  => "FI",
                          "FRANCE"   => "FR",
                          "GREECE"   => "GR",
                          "CROATIA"  => "HR",
                          "HUNGARY"  => "HU",
                          "ITALY"    => "IT",
                          "LATVIA"   => "LV",
                          "LITHUANIA"=> "LT",
                          "MALTA"    => "MT",
                          "POLAND"   => "PL",
                          "PORTUGAL" => "PT",
                          "ROMANIA"  => "RO",
                          "SWEDEN"   => "SE",
                          "NETHERLANDS" => "NL",
                          "LUXEMBOURG"  => "LU",
                          "IRELAND / EIRE" => "IE",
                          "UNITED KINGDOM" => "GB",
                          "CZECH REPUBLIC" => "CZ" );


    // Council areas
    $AREAS_id = array("AGRU", "CONS", "CULT", "ECON", "EDUC", "EMPL", "ENER", "ENVI", "FINA", "FISH", "FOAF", "SANT", "INDU", "INST", "MARK", "JURI", "RESE", "SOCI", "SPAC", "SPOR", "TELE", "TRAN", "YOUT");

    $AGRU_name = "Agriculture"; // 'AGRI' is taken by Council configuration !!
    $CONS_name = "Consumer affairs";
    $CULT_name = "Culture";
    $ECON_name = "Economy";
    $EDUC_name = "Education";
    $EMPL_name = "Employment";
    $ENER_name = "Energy";
    $ENVI_name = "Environment";
    $FINA_name = "Finances";
    $FISH_name = "Fisheries";
    $FOAF_name = "Foreign Affairs";
    $SANT_name = "Health";
    $INDU_name = "Industry";
    $INST_name = "Institutional";
    $MARK_name = "Internal market";
    $JURI_name = "Justice and Home Affairs";
    $RESE_name = "Research";
    $SOCI_name = "Social policy";
    $SPAC_name = "Space";
    $SPOR_name = "Sport";
    $TELE_name = "Telecommunications";
    $TRAN_name = "Transport";
    $YOUT_name = "Youth";


    // Council configurations
    $CONFS_id = array("GA", "FA", "ECOFIN", "JHA", "EPSCO", "COMP", "TTE", "AGRI", "ENV", "EYC");

    $GA_name = "General Affairs";
    $FA_name = "Foreign Affairs";
    $ECOFIN_name = "Economic and Financial Affairs";
    $JHA_name = "Justice and Home Affairs";
    $EPSCO_name = "Employment, Health, Social Affairs";
//    $COMP_name = "Competitiveness (Internal Market, Industry, Research and Space)";
    $COMP_name = "Competitiveness";
    $TTE_name = "Transport, Telecoms and Energy";
    $AGRI_name = "Agriculture and Fisheries";
    $ENV_name = "Environment";
    $EYC_name = "Education, Youth, Culture and Sport";

?>
