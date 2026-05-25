<?php
$tbl="fuel_management_units_evw";
$sqlCols="objectid,fru_code,fmu_name,agn_code,op_stat,op_s_date,op_e_date,per_comp,agn_name";
$sqlColsALT="inc_no,inc_name,inc_status,tfs_region,last_mapped,predicted_size_day0,predicted_hsa_impact_day0,predicted_size_day1,predicted_hsa_impact_day1";
$dataFl="threatened_flora_point";
$dataFn="threatened_fauna_point";
$dataWd="feature_observation_points";
//$dataFlL="frb_threatened_flora_2017";
//$dataFnL="frb_threatened_fauna_2017";
$dataFlL="SELECT species,potential_impact_from_frb_activities AS impact,management_recommendation AS management FROM frb_threatened_flora_2017_lut";
// ORIGINAL LOOKUP TABLE $dataFnL="SELECT species,potential_impact_from_frb_activities AS impact,management_recommendation_known_site AS management from frb_threatened_fauna_2017_lut";
$dataFnL="SELECT species,potential_impact_from_frb_activities AS impact,management_recommendation_known_site AS management from frb_threatened_fauna_2026_lut";
$dataNL="SELECT species,impact,impact_500,impact_1000,management, management_500,management_1000 from frb_eagles_nest_lut";
$dataWdL="SELECT species as species, potential_impact as impact, management_recommendations as management from frb_weeds_lut";
$dataTfiL="SELECT veg_code_d as veg_code, potential_impact as impact, management_recommendation as management from frb_tfi_lut";

?>