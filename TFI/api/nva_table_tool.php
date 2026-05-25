
<!DOCTYPE html>
<html lang="en-us"> 
<head>
 <title>BRU Target FMU  - Flora Reporting</title>
 <meta content="text/html; charset=utf-8" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-measure@3.3.0/dist/leaflet-measure.css" />-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/gokertanrisever/leaflet-ruler@master/src/leaflet-ruler.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet-svg-shape-markers/dist/leaflet-svg-shape-markers.css">

<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
<!--****CUSTOM CSS******-->
<link rel="stylesheet" href="http://risk/BRU_Tools/NV/css/styles.css">
<link rel="stylesheet" href="http://risk/BRU_Tools/NV/css/Control.MiniMaps.css">
<!--********************-->

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-geometryutil@0.10.0/dist/leaflet.geometryutil.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/leaflet-measure@3.1.0/dist/leaflet-measure.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/gh/gokertanrisever/leaflet-ruler@master/src/leaflet-ruler.js"></script>


<!--<script src="https://github.com/themeler/leaflet-triangle-marker/blob/master/leaflet-triangle-marker/leaflet-triangle-marker.js"></script>-->

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"> </script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"> </script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"> </script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"> </script>

<script src="https://unpkg.com/esri-leaflet@3.0.10/dist/esri-leaflet.js"></script>
<script src="https://unpkg.com/esri-leaflet-renderers@3.0.0" crossorigin=""></script>
<script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
<script src="https://unpkg.com/esri-leaflet-vector@4.2.3/dist/esri-leaflet-vector.js" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-svg-shape-markers/dist/leaflet-svg-shape-markers.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/6.0.0/bootbox.min.js" integrity="sha512-oVbWSv2O4y1UzvExJMHaHcaib4wsBMS5tEP3/YkMP6GmkwRJAa79Jwsv+Y/w7w2Vb/98/Xhvck10LyJweB8Jsw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/2.3.1/dataRender/ellipsis.js"></script>

<style>
#ModalMap .modal-dialog {
  width: 100vw !important;
  max-width: 100vw !important;
  height: 100vh !important;
  margin: 0 !important;
}

#ModalMap .modal-content {
  height: 100vh !important;
  border-radius: 0 !important;
}

.slidecontainer {
  width: 100%;
}

.slider {
  -webkit-appearance: none;
  width: 100%;
  height: 15px;
  border-radius: 5px;
  background: #d3d3d3;
  outline: none;
  opacity: 0.7;
  -webkit-transition: .2s;
  transition: opacity .2s;
}

.slider:hover {
  opacity: 1;
}

.slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 25px;
  height: 25px;
  border-radius: 50%;
  background: blue;
  cursor: pointer;
}

.slider::-moz-range-thumb {
  width: 25px;
  height: 25px;
  border-radius: 50%;
  background: blue;
  cursor: pointer;
}

.cluster-flora {
  border-radius: 50%;
  border: 2px solid #0b3d91; 
  color: #ffffff;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
}

.cluster-fauna {
  border-radius: 4px;
  border: 2px solid #004d40;
  color: #ffffff;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
}

#empTable_wrapper.is-loading .dataTables_scrollHead,
#empTable_wrapper.is-loading .dataTables_scroll,
#empTable_wrapper.is-loading .dataTables_scrollHeadInner,
#empTable_wrapper.is-loading .dataTables_scrollHead table,
#empTable_wrapper.is-loading table.dataTable.no-footer {
  border: 0 !important;
  box-shadow: none !important;
}

#empTable_wrapper.is-loading .dataTables_scrollHead thead th,
#empTable_wrapper.is-loading .dataTables_scrollHead thead td {
  border-bottom: 0 !important;
}

div.container {
  max-width: 95%;
  margin: 30;
}

.dataTables_wrapper .dt-buttons {
  text-align: center;
}

.info.legend {
    background: rgba(0, 0, 0, 0.95);
    padding: 10px 14px;
    font: bold 15px Arial, sans-serif;
    color: #fff;
    box-shadow: 0 0 20px rgba(0,0,0,0.3);
    border-radius: 8px;
    border: 2px solid #444;
    line-height: 1.6;
}

.info.legend h4 {
    margin: 0 0 8px;
    font-size: 16px;
    color: #fff;
    text-align: center;
    text-decoration: underline;
}

.legend-item {
    display: flex;
    align-items: center;
    margin-bottom: 6px;
}

.legend-item span {
    margin-left: 8px;
}

.legend-icon {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    display: inline-block;
}

.legend-site-boundary {
    background: transparent;
    border: 2px solid #00008b; /* Dark blue */
}

.legend-buffer {
    background: transparent;
    border: 2px dashed #ccc; /* Gray */
}

</style>
</head>
<body>
<p></p>
<!-- The Table -->
<div class="container-fluid" id="theContainer">
        <table id='empTable' class='display dataTable'>
        <thead id='tblHead' style="display:none">
          <tr>
            <th></th>
            <th>FRU Code</th>
            <th>FMU Name</th>
            <th>AGN Code</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>% Completed</th>
            <th>Agency Name</th>
          </tr>
          <tr>
            <th></th>
            <th>fru_code</th>
            <th>fmu_name</th>
            <th>agn_code</th>
            <th>op_stat</th>
            <th>op_s_date</th>
            <th>op_e_date</th>
            <th>per_comp</th>
            <th>agn_name</th>
          </tr>
        </thead>
        </table>
</div>
<!-- The Map --> 
<div class="modal fade" id="ModalMap" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Target FMU</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div id="map1" style="width:100%; height:100%;"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Close
        </button>
      </div>

    </div>
  </div>
</div>

<!-- The Report -->
<div class="modal" id="ModalReport">
  <div class="modal-dialog modal-lg" style="min-width:90%;">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <!--<h4 class="modal-title w-100 text-center">Flora Report</h4>-->
        <button id="btnExportCombined" class="dt-button btn btn-outline-primary" tabindex="0" aria-controls="empTable" type="button"><span>Export Data</span></button>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>        
      </div>
      <!-- Modal body -->
      <div class="modal-body">
      <form id="reportForm">
        <div>

        <table id='reportTableFlora' class='display dataTable'>
            <thead>
              <tr>
                <th>Species</th>
                <th>Within</th>
                <th>No. Sites</th>
                <th>Search Date</th>
                <th>Impact</th>
                <th>Management</th>
              </tr>
            </thead>
        </table>

        <!--<h4 id="faunaModalTitle" class="modal-title w-100 text-center" style="color:blue;">Fauna Report</h4>-->
        <table id='reportTableFauna' class='display dataTable'>
            <thead>
              <tr>
                <th>Species</th>
                <th>Within</th>
                <th>No. Sites</th>
                <th>Search Date</th>
                <th>Impact</th>
                <th>Management</th>
              </tr>
            </thead>
        </table>

        <table id='reportTableNest' class='display dataTable'>
            <thead>
              <tr>
                <th>Species</th>
                <th>Within</th>
                <th>No. Sites</th>
                <th>Search Date</th>
                <th>Impact</th>
                <th>Management</th>
              </tr>
            </thead>
        </table>

        <table id='reportTableWeed' class='display dataTable'>
            <thead>
              <tr>
                <th>Species</th>
                <th>Within</th>
                <th>No. Sites</th>
                <th>Search Date</th>
                <th>Impact</th>
                <th>Management</th>
              </tr>
            </thead>
        </table>
        
        <!--<table id="reportTableTFI" class="display compact stripe" style="width:100%">
          <thead></thead>
          <tbody></tbody>
          <tfoot>
            <tr>
              <th colspan="2" style="text-align:right">Totals:</th>
              <th></th><th></th><th></th><th></th><th></th><th></th><th></th>
            </tr>
          </tfoot>
        </table>-->

        <table id='reportTableTFI' class='display dataTable'>
            <thead>
              <tr>
                <th>Veg Code D </th>
                <th>Sensitivity</th>
                <th>Total Area</th>
                <th>Search Date</th>
                <th>Outside TFI</th>
                <th>Regenerating</th>
                <th>Vulnerable</th>
                <th>Within TFI</th>
                <th>Within TFI (Frequency Risk)</th>
                <th>Impact</th>
                <th>Management</th>
              </tr>
            </thead>
            <tfoot>
            <tr>
              <th colspan="2" style="text-align:right">Totals:</th>
              <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
            </tr>
          </tfoot>
        </table>

        </div>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </form>
      </div>
      <!-- Modal footer 
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>-->
    </div>
  </div>
</div>

<script>
var map_display;
const apiKey = "AAPK42365fba1e21428fb9a1397b34c53baddY6L1KTHjnlggdQPqy9oghhC2r0jV0_2_ZMsooEvYUza8fFX8yLjZ4AIXpCnIsJn";
var customColumns=[];
var customSearch='';
var customValue='';
var aSQL;
var table;
var tblReportFlora,tblReportFauna 
var btn_highlighted='';
var updateBatch=[];
var map_width,map_height;

var boundaryGeometry, bufferGeometry, bufferGeometry500, bufferGeometry1000;
var featureCluster;
var searchdate;
var legend;
var tfiLayer;

/*function rowCheckedSelect(e){
  console.log("rowCheckedSelect USED");
  updateBatch.length = 0;
    $("#empTable input[type=checkbox").each(function () {     
      var pTD=$(this).parents("td");
      if($(this).val()!="on"){
        if($(e).is(':checked')){
          $('#editBatch').removeClass('disabled');
          updateBatch.push($(this).val());
          $(this).prop( "checked", true );
          $(this).parents("tr").css("background-color","#F0FFFF");
        }else{
          $('#editBatch').addClass('disabled');
          $(this).prop( "checked", false );
          $(this).parents("tr").css("background-color","white");
          updateBatch.length = 0;
        }
      };
    });
};*/

/*function rowChecked(e){
  console.log("rowChecked USED");
  var row=$(e).parents("tr")
  if($(e).is(':checked')){
    $('#editBatch').removeClass('disabled');
    row.css("background-color","#F0FFFF");
    updateBatch.push($(e).val());
  }else{
    $('#editBatch').addClass('disabled');
    row.css("background-color","white");
    var index = updateBatch.indexOf($(e).val());
    updateBatch.splice(index, 1);
  };
};*/

$(document).ready(function(){
  // --- Format today's date as DD/MM/YYYY in local time ---
  searchdate = new Intl.DateTimeFormat('en-AU', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  }).format(new Date());

   let firstLoad = true;
    $('#empTable thead tr:eq(1)').attr("id", "filters");
    $('#filters th').each( function () {
    var title = $(this).text();
      if (title==""){
      }else if(title=="op_stat"){
        var select=$(this).html( '<select style="width:100%;height:26px" class="select_search" id="'+title+'"><option value=""></option></select>')
        $('#'+title).append('<option style="color:grey;" disabled selected>Select option</option>');
        $('#'+title).append('<option value="Burning Today">Burning Today</option>');
        $('#'+title).append('<option value="Complete">Complete</option>');
        $('#'+title).append('<option value="Proposed Soon">Proposed Soon</option>');
        $('#'+title).append('<option value="Planned">Planned</option>');
        $('#'+title).append('<option value="Patrol">Patrol</option>');
      }else if(title=="agn_name"){
        var select=$(this).html( '<select style="width:100%;height:26px" class="select_search" id="'+title+'"><option value=""></option></select>')
        $('#'+title).append('<option value="Tasmania Fire Service">Tasmania Fire Service</option>');
        $('#'+title).append('<option style="color:grey;" disabled selected>Select option</option>');
        $('#'+title).append('<option value="Clarence City Council">Clarence City Council</option>');
        $('#'+title).append('<option value="Devonport City Council">Devonport City Council</option>');
        $('#'+title).append('<option value="Forico">Forico</option>');
        $('#'+title).append('<option value="Ground Proof Mapping">Ground Proof Mapping</option>');
        $('#'+title).append('<option value="Hobart City Council">Hobart City Council</option>');
        $('#'+title).append('<option value="Kingborough Council">Kingborough Council</option>');
        $('#'+title).append('<option value="Launceston City Council">Launceston City Council</option>');
        $('#'+title).append('<option value="Norske Skog">Norske Skog</option>');
        $('#'+title).append('<option value="Parks and Wildlife Service">Parks and Wildlife Service</option>');
        $('#'+title).append('<option value="Reliance Forest Fibre">Reliance Forest Fibre</option>');
        $('#'+title).append('<option value="Sustainable Timber Tasmania">Sustainable Timber Tasmania</option>');
        $('#'+title).append('<option value="Tasmanian Land Conservancy">Tasmanian Land Conservancy</option>'); 
      }else{
        $(this).html( '<input type="text" style="width:100%;height:26px" id="'+title+'" placeholder="Filter" class="text_search"/>' );
      };
    });

  table=$('#empTable')
  .on('processing.dt preDraw.dt', function () {
    $('#empTable_wrapper .dataTables_scroll').css('visibility', 'hidden');
  })
  .DataTable({
     'processing': true,

      "language": {
                        processing: '<span class="sr-only" style="background-color:#FFFFFF;" >Loading...</span> '
                        },

     'serverSide': true,
     'serverMethod': 'post',
     'ajax': {
         'url':'ajaxtarget_fmu.php',
         'data': function(d){
            d.customSearch = customSearch;
         }
     },
     order:[1,'asc'],  
     orderCellsTop: true,
     'columns': [
        {data: 'objectid',
          render: function ( data, type, row, meta ) {
            var mapName="'"+row.fru_code+"'";
            var btnID="'"+data+"'";
            return '<div class="btn-group" role="group">'+
            '<button type="button" id="btn_'+data+'" onclick="rowData('+meta.row+','+btnID+','+mapName+')" class="btn btn-outline-primary">Report</button>'+
            '<button type="button" id="mbtn_'+data+'"onclick="mapID('+btnID+','+data+','+mapName+')" class="btn btn-outline-primary">Show on map.</button></div>';
          }
        },
        { data: 'fru_code' },
        { data: 'fmu_name' },
        { data: 'agn_code' },
        { data: 'op_stat' },
        { data: 'op_s_date' },
        { data: 'op_e_date' },
        { data: 'per_comp' },
        { data: 'agn_name' },
     ],
     'columnDefs': [ {
               'targets': [0], // column index (start from 0)
               'orderable': false, // set orderable false for selected columns
               className: 'text-nowrap',
         }],
         "bFilter": false,    
    dom: 'Blfrtip',
        buttons: [
          {
                text: 'Export all entries',
                className: 'btn btn-outline-primary',
                action: function ( e, dt, node, config ) {
                    console.log("ajaxtarget_fmu.php");
                    $.ajax({
                      type: 'POST',
                      url:'ajaxtarget_fmu.php',
                      data: {ExportToExcel:'Yes',customSearch:customSearch,searchValue:$('input[type="search"]').val()},
                      success: function(data){ 
                        currentDate = new Date().toJSON().slice(0, 10);
                        JSONToCSVConvertor(data, currentDate, true);                    
                      }
                    });
                }
            },
            {
                text: 'Clear all filters',
                className: 'btn btn-outline-primary',
                action: function ( e, dt, node, config ) {
                  clearFilters();
                    customSearch="";
                    customValue="";
                    table
                    .search('')
                    .columns()              
                    .draw()
                }
            },
            {
                text: 'Tools Page',
                className: 'btn btn-outline-danger',
                action: function ( e, dt, node, config ) {
                  window.location.href = 'http://risk/BRU_Tools/';
                }
            },

        ],
     lengthMenu: [[10, 25, 50, 100, 200, 500, 1000], [10, 25, 50, 100, 200, 500, 1000]],
     pageLength: 25,
     scrollCollapse: true,
     scrollY: "70vh",
     createdRow: (row, data, index) => {
      if (data['op_s_date'] && data['op_e_date']){
      var SdateArray = data['op_s_date'].split("/");
      var EdateArray = data['op_e_date'].split("/");
      var SDate = `${SdateArray[2]}/${SdateArray[1]}/${SdateArray[0]}`;
      var EDate = `${EdateArray[2]}/${EdateArray[1]}/${EdateArray[0]}`;
        if (EDate<SDate && (data['per_comp']==0 || data['per_comp']=='') ) {
            $(row).css('color', 'red')
        }
      }
    },
     fnInitComplete: function(settings, json) {
        $.each(json, function(key, value) {
          if(key=='aColumns'){
             let i = 1;
             while (i < value.length) {
                customColumns.push(value[i]); 
                  $('#'+value[i]).on('input', function() {                    
                    var control = 's'+$(this).attr('id'); 
                    customSearch="";
                    customValue="";  
                    customSearch=createCustomSQL(customColumns);         
                    table.draw()
                  });
                  i++;
              }
          };
          });
      }
    });

    table.on('draw', function () {
      $('#tblHead').show();      
      $('#theContainer').show();
    });

    table.on('processing.dt', function (e, settings, processing) {
      if (firstLoad && processing) {
        $('#empTable_wrapper .dataTables_scroll').css('visibility', 'hidden');
      } else if (firstLoad && !processing) {
        // show scroll area again after first draw
        $('#empTable_wrapper .dataTables_scroll').css('visibility', '');
        firstLoad = false; // disable after first load
      } else if (!firstLoad){
        $('#empTable_wrapper .dataTables_scroll').css('visibility', '');
      }
    });

    // also restore on draw just in case
    table.on('draw.dt', function () {
      $('#empTable_wrapper .dataTables_scroll').css('visibility', '');
    });

    $('#btnExportCombined').on('click', () => {
      exportTablesCombined('FMU_Natural_Values_Report.csv'); // default: visible cols + filtered rows
    });

});

function fitTableToScreen() {
  console.log("fitTableToScreen USED");
  const top = $('#theContainer').offset().top;     // where the container starts
  const footerRoom = 250;                          // space for paging/info
  const h = window.innerHeight - top - footerRoom;

  // set the scroll body height
  $('#empTable_wrapper .dataTables_scrollBody').css('max-height', h + 'px');
  $('#empTable_wrapper .dataTables_scrollBody').css('height', h + 'px');

  // tell DataTables to recalc
  table.columns.adjust().draw(false);
}

// run on load and whenever user changes page length
$(window).on('resize', fitTableToScreen);
$('#empTable').on('init.dt length.dt', fitTableToScreen);

// ---------- CSV helpers ----------
function toCsvRow(cells) {
  console.log("toCsvRow USED");
  return cells.map(v => {
    const s = v == null ? '' : String(v);
    const needsQuotes = /[",\n\r]/.test(s);
    const escaped = s.replace(/"/g, '""');
    return needsQuotes ? `"${escaped}"` : escaped;
  }).join(',');
}

function downloadCsv(filename, csvText) {
  console.log("downloadCsv USED");
  const blob = new Blob([csvText], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = filename;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
}

function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
  console.log("JSONToCSVConvertor USED");
    var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;   
    var CSV = '';    
    CSV += 'Date Report exported : '+ReportTitle + '\r\n\n';
    if (ShowLabel) {
        var row = "";        
        for (var index in arrData[0]) {            
            row += index + ',';
        }
        row = row.slice(0, -1);        
        CSV += row + '\r\n';
    }
    
    for (var i = 0; i < arrData.length; i++) {
        var row = "";        
        for (var index in arrData[i]) {
            row += '"' + arrData[i][index] + '",';
        }
        row.slice(0, row.length - 1);        
        CSV += row + '\r\n';
    }

    if (CSV == '') {        
        alert("Invalid data");
        return;
    }   
    
    var fileName = "FMU_Natural_Values_Report_";
    fileName += ReportTitle.replace(/ /g,"_");       
    var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);    
    var link = document.createElement("a");    
    link.href = uri;    
    link.style = "visibility:hidden";
    link.download = fileName + ".csv";    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function rowData(row,btnID,mapName){ 
  console.log("rowData USED");
  btnHighlight();
  $("#btn_"+btnID).removeClass("btn-outline-primary").addClass("btn-primary");
  $("#btn_"+btnID).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...');
  btn_highlighted=btnID;
  createGeojson(btnID,"process");
};

function btnHighlight(){
  console.log("btnHighlight USED")
  if (btn_highlighted){
    $("#btn_"+btn_highlighted).removeClass("btn-primary").addClass("btn-outline-primary");
    $("#mbtn_"+btn_highlighted).removeClass("btn-primary").addClass("btn-outline-primary");
  };
}

/*function dateCtrlFormat(dateArray){
  console.log("dateCtrlFormat USED")
  dateArray=dateArray.split('/');
  var dateVal=`${dateArray[2]}-${dateArray[1]}-${dateArray[0]}`;
  return dateVal;
}*/

function mapID(btnID, data, name){
  console.log("mapID USED")
  $('#ModalMapLabel').text("Fru Code : "+name+", ID : "+data);
  $('#ModalMap').modal('show');
  createGeojson(data,"map")
  btnHighlight();
  $("#mbtn_"+btnID).removeClass("btn-outline-primary").addClass("btn-primary");
  btn_highlighted=btnID;
};

$("#ModalMap").on("hidden.bs.modal", function () {
  console.log("hidden map USED");
  map_display.remove();
});

$("#ModalReport").on("hidden.bs.modal", function () {
  console.log("hidden report USED");
  tblReportFlora.destroy();
  tblReportFauna.destroy();
  tblReportNests.destroy();
  tblReportWeeds.destroy();
  tblReportTFI.destroy();
});

$('#ModalReport').on('shown.bs.modal', function () {
  console.log("shown report USED");
  setTimeout(function () {
    if ($.fn.DataTable.isDataTable('#reportTableFlora')) {
      $('#reportTableFlora').DataTable().columns.adjust().draw(false);
    }
    if ($.fn.DataTable.isDataTable('#reportTableFauna')) {
      $('#reportTableFauna').DataTable().columns.adjust().draw(false);
    }
    if ($.fn.DataTable.isDataTable('#reportTableNest')) {
      $('#reportTableNest').DataTable().columns.adjust().draw(false);
    }
    if ($.fn.DataTable.isDataTable('#reportTableWeed')) {
      $('#reportTableWeed').DataTable().columns.adjust().draw(false);
    }
    if ($.fn.DataTable.isDataTable('#reportTableTFI')) {
      $('#reportTableTFI').DataTable().columns.adjust().draw(false);
    }
  }, 0); // increase to 100–200ms if needed
});

function buildRowsGrouped(features,topic) {
  console.log("buildRowsGrouped USED");
  if (topic != 'nests'){
      const map = new Map();
      for (const f of features) {
        const p = f?.properties ?? {};
        const species = p.species_name ?? '';
        const within  = p.within ?? '';
        const key = species + '|' + within;
        const existing = map.get(key);
        const searchdate = p.searchdate ?? '';

        if (existing) {
          existing.countwithin += 1;
          function parseDMY(s) {
            const [d, m, y] = s.split('/').map(Number);
            return (d && m && y) ? new Date(y, m - 1, d) : null;
          }
          const currDate = parseDMY(searchdate);
          const prevDate = parseDMY(existing.searchdate);

          if (currDate && (!prevDate || currDate > prevDate)) {
            existing.searchdate = searchdate;
          }
        } else {
          map.set(key, {
            species,
            within,
            countwithin: 1,
            searchdate,   // keep first seen; can be overwritten by "latest" logic above
            impact: '',   // Need to add lookup table component here
            management: ''// Need to add lookup table component here
          });
        }
      };
      const uniqueSpecies = Array.from(new Set(Array.from(map.values()).map(r => String(r.species || '').split(';')[0].trim()).filter(Boolean)));
      const aggregatedArr = [];
      aggregatedArr.push(Array.from(map.values()));
      aggregatedArr.push(uniqueSpecies);
      return aggregatedArr;

      //return Array.from(map.values());
    }else{
      const input = (typeof features === 'string') ? JSON.parse(features): features;
      const arr = Array.isArray(input) ? input : [input];
      const flat = Array.isArray(arr[0]) ? arr.flat() : arr;
      const grouped = new Map();
      for (const r of flat) {
        const key = `${r.species}||${r.within}`;
        const existing = grouped.get(key);
        if (existing) {
          existing.countwithin += 1;
        } else {
          grouped.set(key, {
            ...r,
            countwithin: 1 
          });
        }
      }
      const aggregated = Array.from(grouped.values()).map(r => ({
        ...r,
        countwithin: String(r.countwithin)
      }));
      const uniqueSpecies = Array.from(new Set(aggregated.map(r => String(r.species || '').split(';')[0].trim()).filter(Boolean)));
      const aggregatedArr = [];
      aggregatedArr.push(aggregated);
      aggregatedArr.push(uniqueSpecies);
      return aggregatedArr;
    };
}

function floraReportTable(result,btnID){
  console.log("floraReportTable USED");
    let geojson = {};
    try {
      geojson = JSON.parse(result);
    } catch (e) {
      console.error('Invalid JSON:', e);
    }
    const features = Array.isArray(geojson?.features) ? geojson.features : [];
    const floraRows = buildRowsGrouped(features,'flora'); 

    $.ajax({
        url: "lookup.php",
        type: "POST",
        data: { names: floraRows[1], lType:"dataFlL"},
        dataType: 'json',
        success: function (data) {
          var floraData = floraRows[0];
          // ✅ rows can be either data.rows (object response) OR data (array response)
          const rows = Array.isArray(data?.rows) ? data.rows
                    : Array.isArray(data)       ? data
                    : [];

          // ✅ if no returned details, make an empty map (no crashes)
          const dataMap = rows.length
            ? Object.fromEntries(
                rows.map(r => [
                  String(r.species || '').trim(),
                  { impact: r.impact || '', management: r.management || '' }
                ])
              )
            : {}; 

          floraData.forEach(r => {
            const first = String(r.species || '').split(';')[0].trim();
            const hit = dataMap[first];
            if (hit) {
              r.impact = hit.impact;
              r.management = hit.management;
            }
          });

          tblReportFlora = $('#reportTableFlora').DataTable({
              data: floraData,
              columns: [
                { data: 'species',      title: 'Species' },
                { data: 'within',       title: 'Within' },
                { data: 'countwithin',  title: 'No. Sites' },
                { data: 'searchdate',   title: 'Search Date' },
                { data: 'impact',       title: 'Impact' },
                { data: 'management',   title: 'Management' }
              ],
              order: [[0, 'asc']],
              orderCellsTop: true,
              pageLength: 10,
              lengthMenu: [[10, 25, 50, 100, 200, 500, 1000], [10, 25, 50, 100, 200, 500, 1000]],
              scrollCollapse: true,
              scrollY: '900px',
              processing: false,
              serverSide: false,
              bFilter: false,
              // Apply ellipsis renderer to Impact and Management (columns 4 and 5)
              columnDefs: [{
                targets: [4, 5],
                render: function (data, type, row) {
                  if (type === 'display' && $.fn.dataTable?.render?.ellipsis) {
                    return $.fn.dataTable.render.ellipsis(100)(data, type, row);
                  }
                  return data;
                }
              }],
              dom: "<'row'<'col-2 d-flex justify-content-start'i>" +
                  "<'col-4 d-flex justify-content-center toolbarFr'>" +
                  "<'col-4 d-flex justify-content-end'l>>rtp",
              initComplete: function (settings, json) {
                  $("div.toolbarFr").html('<h4 class="modal-title w-100 text-center" style="color:blue;">Flora Report</h4>');        
                  },
              drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
              }
            });
        },
        error: function (xhr, status, err) {
              console.error('Failed to load flora data.',
                'status=', xhr.status,
                'statusText=', xhr.statusText,
                'response=', xhr.responseText,
                'jqStatus=', status,
                'err=', err
              )}
      });    
};

function faunaReportTable(result,btnID){
  console.log("faunaReportTable USED");
  let geojson = {};
    try {
      geojson = JSON.parse(result);
    } catch (e) {
      console.error('Invalid JSON:', e);
    }
    const features = Array.isArray(geojson?.features) ? geojson.features : [];
    const faunaRows = buildRowsGrouped(features,'fauna');

    $.ajax({
        url: "lookup.php",
        type: "POST",
        data: { names: faunaRows[1], lType:"dataFnL"},
        dataType: 'json',
        success: function (data) {
          var faunaData = faunaRows[0];
          // ✅ rows can be either data.rows (object response) OR data (array response)
          const rows = Array.isArray(data?.rows) ? data.rows
                    : Array.isArray(data)       ? data
                    : [];

          // ✅ if no returned details, make an empty map (no crashes)
          const dataMap = rows.length
            ? Object.fromEntries(
                rows.map(r => [
                  String(r.species || '').trim(),
                  { impact: r.impact || '', management: r.management || '' }
                ])
              )
            : {};  // <- nothing returned

          faunaData.forEach(r => {
            const first = String(r.species || '').split(';')[0].trim();
            const hit = dataMap[first];
            if (hit) {
              r.impact = hit.impact;
              r.management = hit.management;
            } else {
              // OPTIONAL: clear fields if no match
              // r.impact = r.impact || '';
              // r.management = r.management || '';
            }
          });

          // --- EMPTY CASE: show title + hide table and stop ---
          /*if (!faunaData || faunaData.length === 0) {
            // show your message where you want it (use modal title or a fixed container)
            // Option A: if you have a modal header title element already:
            $('#faunaModalTitle').text('No fauna records found');

            // hide the actual table so nothing renders
            $('#reportTableFauna').hide();

            return; // <-- IMPORTANT: do not init DataTable
          }

          // --- DATA EXISTS: show normal title + ensure table visible ---
          $('#faunaModalTitle').text('Fauna Report');*/



          tblReportFauna = $('#reportTableFauna').DataTable({
            data: faunaData,
            columns: [
              { data: 'species',      title: 'Species' },
              { data: 'within',       title: 'Within' },
              { data: 'countwithin',  title: 'No. Sites' },
              { data: 'searchdate',   title: 'Search Date' },
              { data: 'impact',       title: 'Impact' },
              { data: 'management',   title: 'Management' }
            ],
            order: [[0, 'asc']],
            orderCellsTop: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, 200, 500, 1000], [10, 25, 50, 100, 200, 500, 1000]],
            scrollCollapse: true,
            scrollY: '900px',
            processing: false,
            serverSide: false,  
            bFilter: false,
            // Apply ellipsis renderer to Impact and Management (columns 4 and 5)
            columnDefs: [{
              targets: [4, 5],
              render: function (data, type, row) {
                if (type === 'display' && $.fn.dataTable?.render?.ellipsis) {
                  return $.fn.dataTable.render.ellipsis(100)(data, type, row);
                }
                return data;
              }
            }],
            dom: "<'row'<'col-2 d-flex justify-content-start'i>" +
                "<'col-4 d-flex justify-content-center toolbarFn'>" +
                "<'col-4 d-flex justify-content-end'l>>rtp",
            initComplete: function (settings, json) {      
                $("div.toolbarFn").html('<h4 class="modal-title w-100 text-center" style="color:blue;">Fauna Report</h4>');
                },
            drawCallback: function () {
              $('[data-toggle="tooltip"]').tooltip();
            }
          });

          tblReportFauna.on('draw', function () {
              if (tblReportFauna.data().length === 0) {
                  tblReportFauna.settings()[0].oScroll.sY = '';
              } else {
                  tblReportFauna.settings()[0].oScroll.sY = '900px';
              }
          });

        },
        error: function (xhr, status, err) {
              console.error('Failed to load fauna data.',
                'status=', xhr.status,
                'statusText=', xhr.statusText,
                'response=', xhr.responseText,
                'jqStatus=', status,
                'err=', err
              )}
      });
}

function raptorReportTable(data,btnID){
  console.log("raptorReportTable USED");
  const nestRows = buildRowsGrouped(data,'nests');
  $.ajax({
        url: "lookup.php",
        type: "POST",
        data: { names: nestRows[1], lType:"dataNL"},
        dataType: 'json',
        success: function (data) {
          var nestData = nestRows[0];
            // Build lookup keyed by the first token of species (to match nest rows)
          const rows = Array.isArray(data?.rows) ? data.rows
                      : Array.isArray(data)       ? data
                      : [];

          const dataMap = rows.length
              ? Object.fromEntries(
                  rows.map(r => [
                    String(r.species || '').split(';')[0].trim(),
                    {
                      impact:           r.impact || '',
                      impact_500:       r.impact_500 || '',
                      impact_1000:      r.impact_1000 || '',
                      management:       r.management || '',
                      management_500:   r.management_500 || '',
                      management_1000:  r.management_1000 || ''
                    }
                  ])
                )
            : {};   // <- if rows is [], map is empty (safe)

          function pickCols(withinRaw = '') {
            const v = String(withinRaw).toLowerCase().trim();
            if (v.includes('1000')) return { impactCol: 'impact_1000', managementCol: 'management_1000' };
            if (v.includes('500'))  return { impactCol: 'impact_500',  managementCol: 'management_500' };
            return { impactCol: 'impact', managementCol: 'management' };
          }

          nestData.forEach(r => {
            const speciesKey = String(r.species || '').split(';')[0].trim();
            const hit = dataMap[speciesKey];
            if (!hit) return;

            const { impactCol, managementCol } = pickCols(r.within);

            r.impact = hit[impactCol] ?? '';
            r.management = hit[managementCol] ?? '';
          });

          tblReportNests = $('#reportTableNest').DataTable({
              data: nestData,
              columns: [
                { data: 'species',      title: 'Species' },
                { data: 'within',       title: 'Within' },
                { data: 'countwithin',  title: 'No. Sites' },
                { data: 'searchdate',   title: 'Search Date' },
                { data: 'impact',       title: 'Impact' },
                { data: 'management',   title: 'Management' }
              ],
              order: [[0, 'asc']],
              orderCellsTop: true,
              pageLength: 10,
              lengthMenu: [[10, 25, 50, 100, 200, 500, 1000], [10, 25, 50, 100, 200, 500, 1000]],
              scrollCollapse: true,
              scrollY: '900px',
              processing: false,
              serverSide: false,
              bFilter: false,
              columnDefs: [{
                targets: [4, 5],
                render: function (data, type, row) {
                  if (type === 'display' && $.fn.dataTable?.render?.ellipsis) {
                    return $.fn.dataTable.render.ellipsis(100)(data, type, row);
                  }
                  return data;
                }
              }],
              dom: "<'row'<'col-2 d-flex justify-content-start'i>" +
                  "<'col-4 d-flex justify-content-center toolbarNt'>" +
                  "<'col-4 d-flex justify-content-end'l>>rtp",
              initComplete: function (settings, json) {
                  $('#ModalReport').modal('show');
                  $("#btn_"+btnID).html('Report');
                  $("div.toolbarNt").html('<h4 class="modal-title w-100 text-center" style="color:blue;">Nest(s) Report</h4>');
                  },
              drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
              }
            });
          },
        error: function (xhr, status, err) {
              console.error('Failed to load fauna data.',
                'status=', xhr.status,
                'statusText=', xhr.statusText,
                'response=', xhr.responseText,
                'jqStatus=', status,
                'err=', err
              )}
      });
};

function weedReportTable(data,btnID){
  console.log("weedReportTable USED");
  const weedRows = buildRowsGrouped(data,'nests');
  $.ajax({
        url: "lookup.php",
        type: "POST",
        data: { names: weedRows[1], lType:"dataWdL"},
        dataType: 'json',
        success: function (data) {
          var weedData = weedRows[0];
        // normalise rows (handles [] or { rows: [] })
        const rows = Array.isArray(data?.rows) ? data.rows
                  : Array.isArray(data)       ? data
                  : [];

        // Build lookup keyed by the first token of species (to match nest rows)
        const dataMap = rows.length
          ? Object.fromEntries(
              rows.map(r => [
                String(r.species || '').split(';')[0].trim(),
                { impact: r.impact || '', management: r.management || '' }
              ])
            )
          : {};   // <- empty map if no returned details

        function pickCols(withinRaw = '') {
          // placeholder for future logic – currently fixed
          return { impactCol: 'impact', managementCol: 'management' };
        }

        // fallback row = where lookup species is blank OR nothing returned
        const fallback = dataMap[''] || { impact: '', management: '' };

        weedData.forEach(r => {
          const speciesKey = String(r.species || '').split(';')[0].trim();

          // if species is blank, do nothing
          if (!speciesKey) return;

          const hit = dataMap[speciesKey] || fallback;

          const { impactCol, managementCol } = pickCols(r.within);
          r.impact = hit[impactCol] ?? '';
          r.management = hit[managementCol] ?? '';
        });

          tblReportWeeds = $('#reportTableWeed').DataTable({
              data: weedData,
              columns: [
                { data: 'species',      title: 'Species' },
                { data: 'within',       title: 'Within' },
                { data: 'countwithin',  title: 'No. Sites' },
                { data: 'searchdate',   title: 'Search Date' },
                { data: 'impact',       title: 'Impact' },
                { data: 'management',   title: 'Management' }
              ],
              order: [[0, 'asc']],
              orderCellsTop: true,
              pageLength: 10,
              lengthMenu: [[10, 25, 50, 100, 200, 500, 1000], [10, 25, 50, 100, 200, 500, 1000]],
              scrollCollapse: true,
              scrollY: '900px',
              processing: false,
              serverSide: false,
              bFilter: false,
              columnDefs: [{
                targets: [4, 5],
                render: function (data, type, row) {
                  if (type === 'display' && $.fn.dataTable?.render?.ellipsis) {
                    return $.fn.dataTable.render.ellipsis(100)(data, type, row);
                  }
                  return data;
                }
              }],
              dom: "<'row'<'col-2 d-flex justify-content-start'i>" +
                  "<'col-4 d-flex justify-content-center toolbarWd'>" +
                  "<'col-4 d-flex justify-content-end'l>>rtp",
              initComplete: function (settings, json) {
                  $("div.toolbarWd").html('<h4 class="modal-title w-100 text-center" style="color:blue;">Weed Report</h4>');        
                  },
              drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
              }
            });
       },
                error: function (xhr, status, err) {
              console.error('Failed to load weed data.',
                'status=', xhr.status,
                'statusText=', xhr.statusText,
                'response=', xhr.responseText,
                'jqStatus=', status,
                'err=', err
              )}
      });
}

function exportTablesCombined(filename = 'FMU_Natural_Values_Report.csv', {
  includeFilteredOnly = true,
  includeVisibleCols  = true
    } = {}) {

      console.log("exportTablesCombined USED");
      const chunks = [];

      const sections = [
        { title: 'Flora Data', dt: tblReportFlora },
        { title: 'Fauna Data', dt: tblReportFauna },
        { title: 'Raptor Nest Data', dt: tblReportNests },
        { title: 'Weed Data', dt: tblReportWeeds },
        { title: 'TFI Data', dt: tblReportTFI }
      ];

      sections.forEach(({ title, dt }, idx) => {
        // Section heading
        chunks.push(toCsvRow([`Table: ${title}`]));
        chunks.push(''); // blank line

        const colIdxs = includeVisibleCols ? dt.columns(':visible').indexes().toArray()
                                          : dt.columns().indexes().toArray();

        // Headers
        const headers = colIdxs.map(i => $(dt.column(i).header()).text().trim());
        chunks.push(toCsvRow(headers));

        // Rows
        const rowSel = includeFilteredOnly ? { search: 'applied' } : { search: 'none' };
        const rows = dt.rows(rowSel).data().toArray();

        rows.forEach(row => {
          if (Array.isArray(row)) {
            chunks.push(toCsvRow(colIdxs.map(i => row[i])));
          } else {
            const vals = colIdxs.map(i => {
              const settings = dt.settings()[0].aoColumns[i];
              const dataSrc = settings.mData;
              return typeof dataSrc === 'function' ? dataSrc(row, 'display') : row?.[dataSrc];
            });
            chunks.push(toCsvRow(vals));
          }
        });

        if (idx < sections.length - 1) {
          chunks.push(''); // blank line between sections
        }
      });

      const filedate = new Date().toISOString().slice(0,10).replace(/-/g,'');

      downloadCsv(filedate+"_"+filename, chunks.join('\r\n'));
}

function createGeojson(objectid,process){
  console.log("createGeojson USED");
  boundaryGeometry = null;
  if (process=='map'){
    map_display = L.map("map1",{      
            zoom: 10,
            minZoom: 3,
            maxZoom: 19,
            closePopupOnClick: false
    });
  };
    $.ajax({
        url: "target_fmu_query.php",
        type: "POST",
        data: { objectid: objectid },
        success: function (result) {
            var geojson = JSON.parse(result);
            if (process == "map"){
              map_display.on('click', function (ev) {
                ev.originalEvent.stopPropagation();
              });
            };
            if (geojson.features && geojson.features.length > 0) {
                boundaryGeometry = geojson.features[0].geometry;
                targetBuffer(process,objectid);                  
            } else {
                console.error("No boundary geometry found!");
            }
            if (process == "map"){
              mapPrep(objectid,fru_code);
              var mainPoly  = L.geoJSON(geojson).addTo(map_display);
              var b = mainPoly.getBounds();
              var latPad = 500 / 111320;
              var lngPad = 500 / (111320 * Math.cos((b.getCenter().lat) * Math.PI / 180));
              var paddedBounds = L.latLngBounds(
                  [b.getSouth() - latPad, b.getWest() - lngPad],
                  [b.getNorth() + latPad, b.getEast() + lngPad]
              );
              map_display.fitBounds(paddedBounds)
              //map_display.fitBounds(b)
            };
        }      
      });
};

function targetBuffer(process,objectid){
  console.log("targetBuffer USED");
  $.ajax({
      url: "target_fmu_query_buffer.php",
      type: "POST",
      data: { objectid: objectid },
      dataType: "json",
      success: function (bufferGeojson) {
        if (process == "map"){
          var myStyle = {
              weight: 2,
              opacity: 1,
              color: 'white', 
              fillOpacity: 0.1,
              dashArray: '6 4'
          };
          var myBuffLayer = L.geoJSON(bufferGeojson, { style: myStyle }).addTo(map_display);
        };

          // Extract buffer geometry
          bufferGeometry = null;
          if (bufferGeojson.features && bufferGeojson.features.length > 0) {
              bufferGeometry = bufferGeojson.features[0].geometry;
              bufferGeometry500 = bufferGeojson.features[0].properties.geojson_500;
              bufferGeometry1000 = bufferGeojson.features[0].properties.geojson_1000;
          }                        

          // Add MiniMap
          if (process == "map"){
            var overview = L.esri.Vector.vectorBasemapLayer("OSM:Standard", {
                apikey: apiKey,
                minZoom: 0,
                maxZoom: 13
            });
            var miniMap = new L.Control.MiniMap(overview, {
                zoomLevelOffset: -7,
                toggleDisplay: true
            }).addTo(map_display);
          };
          targetFlora(process,objectid,boundaryGeometry,bufferGeometry,bufferGeometry500,bufferGeometry1000);            
      }
  });
};

function targetFlora(process,objectid,boundaryGeometry,bufferGeometry,bufferGeometry500,bufferGeometry1000){
  console.log("targetFlora USED");                        
          $.ajax({
            url: "target_fmu_query_report.php",
            type: "POST",
            data: {
                boundary_geometry: JSON.stringify(boundaryGeometry),
                buffer_geometry: JSON.stringify(bufferGeometry),
                data: "dataFl"
            },
            success: function (result) {
              if (process == "map"){                                                                                        
                  const floraLayer = L.geoJSON(JSON.parse(result), {
                  pointToLayer: function (feature, latlng) {
                    const within200   = feature.properties.within === 'within 200m';
                    const fillColor   = within200 ? "#0d6c0dff" : "#90ee90";
                    const borderColor = within200 ? "#2c99dcff" : "#ffffff"; 
                    const svg =
                      `<svg width="14" height="14" viewBox="0 0 22 22">
                        <polygon points="11,2 20,20 2,20"
                                  fill="${fillColor}" stroke="${borderColor}" stroke-width="2"/>
                      </svg>`;
                    const icon = L.divIcon({
                      className: 'triangle-icon',
                      html: svg,
                      iconSize: [14, 14],
                      iconAnchor: [7, 14] 
                    });
                    return L.marker(latlng, { icon });
                  },

                  onEachFeature: function (feature, layer) {
                    layer.on('click', function (e) {
                      const html =
                        `<b>Species:</b> ${feature.properties.species_name}<br>` +
                        `<b>Location:</b> ${feature.properties.within}`;
                      L.popup({
                        offset: L.point(0, -10) 
                      })
                      .setLatLng(e.latlng)
                      .setContent(html) 
                      .openOn(map_display); 
                    });
                  }
                });
                featureCluster.addLayer(floraLayer);
                //targetFauna(process,objectid,boundaryGeometry,bufferGeometry,bufferGeometry500,bufferGeometry1000)
              }else{
                floraReportTable(result,objectid);
              }
              targetFauna(process,objectid,boundaryGeometry,bufferGeometry,bufferGeometry500,bufferGeometry1000)
            }
          });
};

function targetFauna(process,objectid,boundaryGeometry,bufferGeometry,bufferGeometry500,bufferGeometry1000){
  console.log("targetFauna USED");  
  $.ajax({
              url: "target_fmu_query_report.php",
              type: "POST",
              data: {
                  boundary_geometry: JSON.stringify(boundaryGeometry),
                  buffer_geometry: JSON.stringify(bufferGeometry),
                  data: "dataFn"
              },
              success: function (result) {
                if (process == "map"){                                
                  var faunaLayer = L.geoJSON(JSON.parse(result), {
                      pointToLayer: function (feature, latlng) {
                          var fillColor, borderColor, dashStyle;

                          if (feature.properties.within === 'within 200m') {
                              fillColor = "#ece517ff"; // Light green
                              borderColor = "#000"; // Black border
                              //dashStyle = "1 4"; // Dashed outline
                          } else {
                              fillColor = "#e8ac13ff"; // Green
                              borderColor = "#ffffff"; // White border
                              //dashStyle = null; // Solid outline
                          }

                          return L.circleMarker(latlng, {
                              radius: 6,
                              fillColor: fillColor,
                              color: borderColor,
                              weight: 2,
                              opacity: 1,
                              fillOpacity: 0.8,
                              //dashArray: dashStyle // Apply dashed border for 200m
                          });
                      },
                      onEachFeature: function (feature, layer) {
                          layer.bindPopup(
                              "<b>Species:</b> " + feature.properties.species_name +
                              "<br><b>Location:</b> " + feature.properties.within
                          );
                      }
                  }).addTo(map_display);                               
                  featureCluster.addLayer(faunaLayer);
                  //nests(process,objectid,bufferGeometry500,bufferGeometry1000);
                }else{
                  faunaReportTable(result,objectid);
                }
              }
            });
            //nests(process,objectid,bufferGeometry500,bufferGeometry1000);
            targetWeed(process,objectid,boundaryGeometry,bufferGeometry,bufferGeometry500,bufferGeometry1000);
};

function targetWeed(process,objectid,boundaryGeometry,bufferGeometry,bufferGeometry500,bufferGeometry1000){
  console.log("targetWeed USED");  
  $.ajax({
      url: "target_fmu_query_weeds.php",
      type: "POST",
      data: { 
        objectid:objectid,
        bufferGeometry:JSON.stringify(bufferGeometry),
        boundaryGeometry:JSON.stringify(boundaryGeometry)
      },
      dataType: 'json',
      success: function (geojson) {
        if (process == "map"){
          var weedLayer = L.geoJSON(geojson, {
                      pointToLayer: function (feature, latlng) {
                          var fillColor, borderColor, dashStyle;
                          if (feature.properties.within === 'within 200m') {
                              fillColor = "#ec1717ff"; // Light green
                              borderColor = "#000"; // Black border
                              //dashStyle = "1 4"; // Dashed outline
                          } else {
                              fillColor = "#ec17b3ff"; // Green
                              borderColor = "#ffffff"; // White border
                              //dashStyle = null; // Solid outline
                          }
                          return L.circleMarker(latlng, {
                              radius: 6,
                              fillColor: fillColor,
                              color: borderColor,
                              weight: 2,
                              opacity: 1,
                              fillOpacity: 0.8,
                              //dashArray: dashStyle // Apply dashed border for 200m
                          });
                      },
                      onEachFeature: function (feature, layer) {
                var props = feature.properties;
                var popupContent = `
                    <b>Species:</b> ${props.SPECIES_NAME || 'Unknown'}<br>
                    <b>Common Name:</b> ${props.PREFERRED_COMMON_NAMES || 'N/A'}
                `;
                layer.bindPopup(popupContent);
            }
        }).addTo(map_display);
        featureCluster.addLayer(weedLayer);
          //nests(process,objectid,bufferGeometry500,bufferGeometry1000);
        }else{
            var weedMerged = [];
            var data = nFormat(geojson, 'weed');
            weedMerged.push(data)        
            weedReportTable(weedMerged,objectid);
        };
      },                                
      error: function (xhr, status, err) {
          console.error('Failed to load weed data.',
            'status=', xhr.status,
            'statusText=', xhr.statusText,
            'response=', xhr.responseText,
            'jqStatus=', status,
            'err=', err
          );
        }
  });       
  nests(process,objectid,bufferGeometry500,bufferGeometry1000);
  tfi(process,objectid,boundaryGeometry);
};

function nFormat(geojson, withinVal) {
    console.log("nFormat USED"); 
    const data1 = geojson.features.map(f => {
    const p = f.properties;

    // Decide within using an if statement
    let within;
    if (withinVal === "" || withinVal == 'weed') {
      within = p.within;
    } else {
      within = withinVal;
    }

    if (withinVal == 'weed'){
      species = [p.SPECIES_NAME,
        p.PREFERRED_COMMON_NAMES,
        p.INTRODUCED_WATCH_LIST];
    }else{
      species = [p.SPECIES_NAME,
        p.PREFERRED_COMMON_NAMES,
        p.STATE_SCHEDULE_DESC,
        p.NATIONAL_SCHEDULE_DESC];
    };

    return {
     /* species: [
        p.SPECIES_NAME,
        p.PREFERRED_COMMON_NAMES,
        p.STATE_SCHEDULE_DESC,
        p.NATIONAL_SCHEDULE_DESC
      ]*/
     species:species
        .map(v => (v && String(v).trim() !== "" ? v : "N/A"))
        .join("; "),
      within: within,
      countwithin: "",
      searchdate: searchdate,
      impact: "",
      management: ""
    };
  });

  return data1;
}

function nests(process,objectid,bufferGeometry500,bufferGeometry1000){
  console.log("nests USED"); 
  $.ajax({
    url: "target_fmu_query_nests.php",
    type: "POST",
    data: { objectid: objectid },
    dataType: 'json',
    success: function (geojson) {
      if (process == "map"){
        var nestsLayer = L.geoJSON(geojson, {                               
              pointToLayer: function (feature, latlng) {
                return L.shapeMarker(latlng, {
                  shape: 'x',       // ← draw a cross instead of a circle
                  radius: 10,            // size in pixels; adjust to taste
                  color: '#eb0f0fff',        // stroke (border) color
                  weight: 2,            // stroke width
                  fillColor: '#000', // fill color inside the cross arms
                  fillOpacity: 0,
                  opacity: 1,
                  pane: "popupPane"
                });
              },

            onEachFeature: function (feature, layer) {
                var props = feature.properties;
                var popupContent = `
                    <b>Species:</b> ${props.SPECIES_NAME || 'Unknown'}<br>
                    <b>Common Name:</b> ${props.PREFERRED_COMMON_NAMES || 'N/A'}
                `;
                layer.bindPopup(popupContent);
            }
        }).addTo(map_display);
        nests500m(process,objectid,bufferGeometry500,bufferGeometry1000,'');
      }else{
        var data = nFormat(geojson, 'within');
        nests500m(process,objectid,bufferGeometry500,bufferGeometry1000,data);
      };
    },
    error: function (xhr, status, err) {
          console.error('Failed to load nests data.',
            'status=', xhr.status,
            'statusText=', xhr.statusText,
            'response=', xhr.responseText,
            'jqStatus=', status,
            'err=', err
          )}
  });
};

function nests500m(process,objectid,bufferGeometry500,bufferGeometry1000,nestdata){
  console.log("nests500 USED"); 
    $.ajax({
      url: "target_fmu_query_nests_buffer.php",
      type: "POST",
      data: { objectid: objectid,bufferGeometry:JSON.stringify(bufferGeometry500)},
      dataType: 'json',
      success: function (geojson) {
        if (process == "map"){
          var nestsLayer = L.geoJSON(geojson, {                               
                pointToLayer: function (feature, latlng) {
                  return L.shapeMarker(latlng, {
                    shape: 'x',       // ← draw a cross instead of a circle
                    radius: 10,            // size in pixels; adjust to taste
                    color: '#510febff',        // stroke (border) color
                    weight: 2,            // stroke width
                    fillColor: '#000', // fill color inside the cross arms
                    fillOpacity: 0,
                    opacity: 1,
                    pane: "popupPane"
                  });
                },

              onEachFeature: function (feature, layer) {
                  var props = feature.properties;
                  var popupContent = `
                      <b>Species:</b> ${props.SPECIES_NAME || 'Unknown'}<br>
                      <b>Common Name:</b> ${props.PREFERRED_COMMON_NAMES || 'N/A'}
                  `;
                  layer.bindPopup(popupContent);
              }
          }).addTo(map_display);
          nests1000m(process,objectid,bufferGeometry500,bufferGeometry1000,'');
        }else{
            var nestMerged = [];
            var data = nFormat(geojson, 'within 500m');
            nestMerged.push(nestdata);
            nestMerged.push(data);
            nests1000m(process,objectid,bufferGeometry500,bufferGeometry1000,nestMerged);
        };
      },                                
      error: function (xhr, status, err) {
          console.error('Failed to load nests data.',
            'status=', xhr.status,
            'statusText=', xhr.statusText,
            'response=', xhr.responseText,
            'jqStatus=', status,
            'err=', err
          );
        }
  });
};

function nests1000m(process,objectid,bufferGeometry500,bufferGeometry1000,nestMerged){
  console.log("nest1000m USED"); 
  $.ajax({
      url: "target_fmu_query_nests_buffer.php",
      type: "POST",
      data: { objectid: objectid,bufferGeometry:JSON.stringify(bufferGeometry1000)},
      dataType: 'json',
      success: function (geojson) {
        if (process == "map"){
          var nestsLayer = L.geoJSON(geojson, {                               
                pointToLayer: function (feature, latlng) {
                  return L.shapeMarker(latlng, {
                    shape: 'x',
                    radius: 10,
                    color: '#d90febff', 
                    weight: 2,
                    fillColor: '#000',
                    fillOpacity: 0,
                    opacity: 1,
                    pane: "popupPane"
                  });
                },

              onEachFeature: function (feature, layer) { 
                  var props = feature.properties;
                  var popupContent = `
                      <b>Species:</b> ${props.SPECIES_NAME || 'Unknown'}<br>
                      <b>Common Name:</b> ${props.PREFERRED_COMMON_NAMES || 'N/A'}
                  `;
                  layer.bindPopup(popupContent);
              }
          }).addTo(map_display);
        }else{  
          var data = nFormat(geojson, 'within 1000m');
          nestMerged.push(data)        
          raptorReportTable(nestMerged,objectid);
        };
      },
      error: function (xhr, status, err) {
          console.error('Failed to load nests data.',
            'status=', xhr.status,
            'statusText=', xhr.statusText,
            'response=', xhr.responseText,
            'jqStatus=', status,
            'err=', err
          );
        }
  });
};

function tfiStyle(feature) {
  switch (feature.properties.tfi_category) {
    case 'Within TFI':
      return { color: '#4C7300', weight: 1, fillOpacity: 0.6 };
    case 'Within TFI (Frequency Risk)':
      return { color: '#FFFF00', weight: 1, fillOpacity: 0.6 };
    case 'Outside TFI':
      return { color: '#002673', weight: 1, fillOpacity: 0.6 };
    case 'Vulnerable':
      return { color: '#734C00', weight: 1, fillOpacity: 0.7 };
    case 'Regenerating':
      return { color: '#A8A800', weight: 1, fillOpacity: 0.6 };
    default:
      return { color: '#95A5A6', weight: 1, fillOpacity: 0.4 };
  }
};

function buildTfiRows(featureCollection, searchDateStr) {
  const rowsByKey = new Map();

  // normalise categories to column keys
  const catToKey = (catRaw) => {
    const cat = (catRaw || "").trim().toLowerCase();
    if (cat === "outside tfi") return "outside_tfi_ha";
    if (cat === "regenerating") return "regenerating_ha";
    if (cat === "vulnerable") return "vulnerable_ha";
    // catch both "Within TFI (frequency risk)" and case variations
    if (cat.includes("frequency risk")) return "within_tfi_freq_risk_ha";
    if (cat === "within tfi") return "within_tfi_ha";
    return null; // unknown category -> ignored (or handle if you want)
  };

  const toHa = (props) => {
    // Prefer supplied area_ha, otherwise compute from m2
    const ha = Number(props?.area_ha);
    if (Number.isFinite(ha)) return ha;
    const m2 = Number(props?.area_m2);
    if (Number.isFinite(m2)) return m2 / 10000.0;
    return 0;
  };

  for (const f of (featureCollection?.features || [])) {
    const p = f.properties || {};

    // Accept either your property names OR the “spec” names if you ever switch:
    const veg = (p.vegetationcode ?? p.VEGCODE_D ?? "").toString();
    const sens = (p.firesens ?? p.FIRESENSE ?? "").toString();
    const cat = (p.tfi_category ?? p.TFI_CAT ?? "").toString();

    const key = `${veg}||${sens}`;
    if (!rowsByKey.has(key)) {
      rowsByKey.set(key, {
        veg_code_d: veg,
        sensitivity: sens,
        search_date: searchDateStr || "",
        outside_tfi_ha: 0,
        regenerating_ha: 0,
        vulnerable_ha: 0,
        within_tfi_ha: 0,
        within_tfi_freq_risk_ha: 0,
        total_area_ha: 0
      });
    }

    const row = rowsByKey.get(key);
    const areaHa = toHa(p);
    const colKey = catToKey(cat);
    if (colKey) row[colKey] += areaHa;
  }

  // compute totals + rounding
  const out = Array.from(rowsByKey.values()).map(r => {
    r.total_area_ha =
      r.outside_tfi_ha +
      r.regenerating_ha +
      r.vulnerable_ha +
      r.within_tfi_ha +
      r.within_tfi_freq_risk_ha;

    // round to 2 decimals like your UI expectations
    for (const k of [
      "outside_tfi_ha","regenerating_ha","vulnerable_ha",
      "within_tfi_ha","within_tfi_freq_risk_ha","total_area_ha"
    ]) {
      r[k] = Math.round((r[k] + Number.EPSILON) * 100) / 100;
    }
    return r;
  });

  return out;
};

function tfiReportTable(groupedRows){
  const num2 = $.fn.dataTable.render.number(',', '.', 2, '').display; 
  const uniqueVegCodes = [...new Set(groupedRows.map(r => r.veg_code_d))].join(',');

  console.log("tfiReportTable USED");
  
  $.ajax({
        url: "lookup.php",
        type: "POST",
        data: { names: uniqueVegCodes, lType:"dataTfiL"},
        dataType: 'json',
        success: function (data) {
          // ✅ rows can be either data.rows (object response) OR data (array response)
          const rows = Array.isArray(data?.rows) ? data.rows
                    : Array.isArray(data)       ? data
                    : [];
          // ✅ if no returned details, make an empty map (no crashes)
          const dataMap = rows.length
            ? Object.fromEntries(
                rows.map(r => [
                  String(r.veg_code || '').trim(),
                  { impact: r.impact || '', management: r.management || '' }
                ])
              )
            : {};  // <- nothing returned

          groupedRows.forEach(r => {
            const key = String(r.veg_code_d || '').split(';')[0].trim();
            const hit = dataMap[key];

            r.impact = hit?.impact ?? '';
            r.management = hit?.management ?? '';
          });

          tblReportTFI = $('#reportTableTFI').DataTable({
              data: groupedRows,
              columns: [
              { data: 'veg_code_d', title: 'Veg Code D' },
              { data: 'sensitivity', title: 'Sensitivity' },
              { data: 'total_area_ha', title: 'Total Area', render: num2 },
              { data: 'search_date', title: 'Search Date' },
              { data: 'outside_tfi_ha', title: 'Outside TFI', render: num2 },
              { data: 'regenerating_ha', title: 'Regenerating', render: num2 },
              { data: 'vulnerable_ha', title: 'Vulnerable', render: num2 },
              { data: 'within_tfi_ha', title: 'Within TFI', render: num2 },
              { data: 'within_tfi_freq_risk_ha', title: 'Within TFI (Frequency Risk)', render: num2 },      
              { data: 'impact', title: 'Impact'},
              { data: 'management', title: 'Management'}
              ],
              order: [[0, 'asc']],
              orderCellsTop: true,
              pageLength: 10,
              lengthMenu: [[10, 25, 50, 100, 200, 500, 1000], [10, 25, 50, 100, 200, 500, 1000]],
              scrollCollapse: true,
              scrollY: '900px',
              processing: false,
              serverSide: false,
              bFilter: false,
              // Optional: footer totals across the whole table
              footerCallback: function () {
                const api = this.api();
                const sumCol = (idx) => api
                  .column(idx, { search: 'applied' })
                  .data()
                  .reduce((a, b) => (Number(a) || 0) + (Number(b) || 0), 0);

                // Total Area + 5 bucket cols + within risk col are numeric
                const totals = {
                  total: sumCol(2),
                  outside: sumCol(4),
                  regen: sumCol(5),
                  vuln: sumCol(6),
                  within: sumCol(7),
                  withinRisk: sumCol(8)
                };

                // Put grand total under "Total Area" footer cell
                $(api.column(2).footer()).html(num2(totals.total));
                $(api.column(4).footer()).html(num2(totals.outside));
                $(api.column(5).footer()).html(num2(totals.regen));
                $(api.column(6).footer()).html(num2(totals.vuln));
                $(api.column(7).footer()).html(num2(totals.within));
                $(api.column(8).footer()).html(num2(totals.withinRisk));
              },
              columnDefs: [{
                targets: [9, 10],
                render: function (data, type, row) {
                  if (type === 'display' && $.fn.dataTable?.render?.ellipsis) {
                    return $.fn.dataTable.render.ellipsis(100)(data, type, row);
                  }
                  return data;
                }
              }],
              dom: "<'row'<'col-2 d-flex justify-content-start'i>" +
                  "<'col-4 d-flex justify-content-center toolbarTFI'>" +
                  "<'col-4 d-flex justify-content-end'l>>rtp",
              initComplete: function (settings, json) {
                  $("div.toolbarTFI").html('<h4 class="modal-title w-100 text-center" style="color:blue;">TFI Report</h4>');        
                  },
              drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
              }
            });
        },
                error: function (xhr, status, err) {
              console.error('Failed to load TI data.',
                'status=', xhr.status,
                'statusText=', xhr.statusText,
                'response=', xhr.responseText,
                'jqStatus=', status,
                'err=', err
              )}
      });

  //return table;
};

function tfi(process,objectid,boundaryGeometry){
  console.log("tfi USED"); 
  $.ajax({
      url: "target_fmu_query_tfi.php",
      type: "POST",
      data: { 
        objectid:objectid,
        boundaryGeometry:JSON.stringify(boundaryGeometry)
      },
      dataType: 'json',
      success: function (geojson) {
        if (process == "map") {
          let selectedLayer = null;
          const defaultStyle = tfiStyle; 
          function selectedStyle(layer) {
            const base = (typeof defaultStyle === "function")
              ? defaultStyle(layer.feature)
              : { ...defaultStyle };
            return {
              ...base,
              weight: 4,
              color: "#00FFFF",     // outline colour
              fillOpacity: 0.55     // slightly stronger fill
            };
          }

          tfiLayer = L.geoJSON(geojson, {
            style: defaultStyle,
            onEachFeature: function (feature, layer) {
              layer.bindPopup(
                `<b>TFI Category:</b> ${feature.properties.tfi_category}<br>
                <b>Veg Code:</b> ${feature.properties.vegetationcode}<br>
                <b>Area (ha):</b> ${feature.properties.area_ha}`
              );

              layer.on("click", function (e) {
                if (selectedLayer) {
                  tfiLayer.resetStyle(selectedLayer);
                }
                selectedLayer = e.target;
                selectedLayer.setStyle(selectedStyle(selectedLayer));
                if (selectedLayer.bringToFront) selectedLayer.bringToFront();
                selectedLayer.openPopup();
              });
            }
          }).addTo(map_display);

          map_display.on("click", function () {
            if (selectedLayer) {
              tfiLayer.resetStyle(selectedLayer);
              selectedLayer = null;
            }
          });
        }else{

          //const searchDate = new Date().toLocaleDateString('en-AU'); // or pass your own
          const grouped = buildTfiRows(geojson, searchdate);
          tfiReportTable(grouped);
        };

      },                                
      error: function (xhr, status, err) {
          console.error('Failed to load tfi data.',
            'status=', xhr.status,
            //'statusText=', xhr.statusText,
            //'response=', xhr.responseText,
            //'jqStatus=', status,
            'err=', err
          );
        }
  });    
};

function mapPrep(objectid,fru_code){
  console.log("mapPrep USED"); 
    legend = L.control({ position: 'bottomleft' });
    legend.onAdd = function (map) {
        var div = L.DomUtil.create('div', 'info legend');
        div.innerHTML += '<h4>Map Legend</h4>';

        // SITE //
        div.innerHTML += '<div class="legend-item"><svg width="20" height="20" viewBox="0 0 20 20" style="vertical-align: middle; margin-right:6px;">'+
                         '<circle cx="10" cy="10" r="8" fill="rgba(173,216,230,0.5)" stroke="#3B82F6" stroke-width="3"/></svg><span>Site Boundary</span></div>';
        div.innerHTML += '<div class="legend-item"><svg width="20" height="20" viewBox="0 0 20 20" style="vertical-align: middle; margin-right:6px;">'+
                         '<circle cx="10" cy="10" r="8" fill="none" stroke="#ffffff" stroke-width="2" stroke-dasharray="4,3"/></svg><span>200m Buffer</span></div>';                
        // FLORA //
        div.innerHTML += '<div class="legend-item"><svg width="20" height="20" viewBox="0 0 24 24" style="vertical-align: middle; margin-right:6px;">'+'<polygon points="12,0 0,24 24,24" fill="#32a852" stroke="#fff" stroke-width="2"/>' +
          '</svg><span>Flora</span></div>';
        div.innerHTML += '<div class="legend-item"><svg width="20" height="20" viewBox="0 0 24 24" style="vertical-align: middle; margin-right:6px;">'+'<polygon points="12,0 0,24 24,24" fill="#0d6c0dff" stroke="#2c99dcff" stroke-width="2"/>' +
          '</svg><span>Flora (In Buffer)</span></div>';

        // FAUNA //
        div.innerHTML += '<div class="legend-item"><svg width="20" height="20" viewBox="0 0 20 20" style="vertical-align: middle; margin-right:6px;">'+'<circle cx="10" cy="10" r="8" fill="#ffa500" stroke="#ffffff" stroke-width="2"/></svg><span>Fauna</span></div>';
        div.innerHTML += '<div class="legend-item"><svg width="20" height="20" viewBox="0 0 20 20" style="vertical-align: middle; margin-right:6px;">'+'<circle cx="10" cy="10" r="8" fill="#ffff00" stroke="#000000" stroke-width="2"/></svg><span>Fauna (In Buffer)</span></div>'; 
        
        // WEED //
        div.innerHTML += '<div class="legend-item"><svg width="20" height="20" viewBox="0 0 20 20" style="vertical-align: middle; margin-right:6px;">'+'<circle cx="10" cy="10" r="8" fill="#ec17b3ff" stroke="#ffffff" stroke-width="2"/></svg><span>Weed</span></div>';
        div.innerHTML += '<div class="legend-item"><svg width="20" height="20" viewBox="0 0 20 20" style="vertical-align: middle; margin-right:6px;">'+'<circle cx="10" cy="10" r="8" fill="#ec1717ff" stroke="#000" stroke-width="2"/></svg><span>Weed (In Buffer)</span></div>';
                         
        // RAPTORS //
        div.innerHTML += '<div class="legend-item"><svg width="20" height="20" viewBox="0 0 20 20" style="vertical-align: middle; margin-right:6px;">'+'<line x1="4" y1="4" x2="16" y2="16" stroke="#eb0f0f" stroke-width="3"/>'+
                         '<line x1="16" y1="4" x2="4" y2="16" stroke="#eb0f0f" stroke-width="3"/></svg><span>Raptor Nest</span></div>';
        div.innerHTML += '<div class="legend-item"><svg width="20" height="20" viewBox="0 0 20 20" style="vertical-align: middle; margin-right:6px;">'+'<line x1="4" y1="4" x2="16" y2="16" stroke="#510febff" stroke-width="3"/>'+
                         '<line x1="16" y1="4" x2="4" y2="16" stroke="#510febff" stroke-width="3"/></svg><span>Raptor Nest within 500m</span></div>';
        div.innerHTML += '<div class="legend-item"><svg width="20" height="20" viewBox="0 0 20 20" style="vertical-align: middle; margin-right:6px;">'+'<line x1="4" y1="4" x2="16" y2="16" stroke="#d90febff" stroke-width="3"/>'+
                         '<line x1="16" y1="4" x2="4" y2="16" stroke="#d90febff" stroke-width="3"/></svg><span>Raptor Nest within 1000m</span></div>';

                         // TFI //
        //div.innerHTML += '<h4>Tolerable Fire Intervals</h4>';

        div.innerHTML += `
          <div class="legend-toggle">
            <label style="display:flex; align-items:center; gap:8px; font-weight:600; margin:6px 0;">
              <input id="toggle-tfi" type="checkbox" checked />
              <span>Tolerable Fire Intervals</span>
            </label>
          </div>
        `;

        div.innerHTML += `
          <div class="legend-item">
            <svg width="20" height="20" viewBox="0 0 20 20" style="vertical-align: middle; margin-right:6px;">
              <rect x="2" y="2" width="16" height="16" fill="#4C7300" stroke="#4C7300"/>
            </svg>
            <span>Within TFI</span>
          </div>`;

        div.innerHTML += `
          <div class="legend-item">
            <svg width="20" height="20" viewBox="0 0 20 20" style="vertical-align: middle; margin-right:6px;">
              <rect x="2" y="2" width="16" height="16" fill="#FFFF00" stroke="#FFFF00"/>
            </svg>
            <span>Within TFI (Frequency Risk)</span>
          </div>`;

        div.innerHTML += `
          <div class="legend-item">
            <svg width="20" height="20" viewBox="0 0 20 20" style="vertical-align: middle; margin-right:6px;">
              <rect x="2" y="2" width="16" height="16" fill="#002673" stroke="#002673"/>
            </svg>
            <span>Outside TFI</span>
          </div>`;

        div.innerHTML += `
          <div class="legend-item">
            <svg width="20" height="20" viewBox="0 0 20 20" style="vertical-align: middle; margin-right:6px;">
              <rect x="2" y="2" width="16" height="16" fill="#734C00" stroke="#734C00"/>
            </svg>
            <span>Vulnerable</span>
          </div>`;

        div.innerHTML += `
          <div class="legend-item">
            <svg width="20" height="20" viewBox="0 0 20 20" style="vertical-align: middle; margin-right:6px;">
              <rect x="2" y="2" width="16" height="16" fill="#A8A800" stroke="#A8A800"/>
            </svg>
            <span>Regenerating</span>
          </div>`;
                return div;
            };

    legend.addTo(map_display);

    // stop clicks inside legend from zooming/dragging the map
    L.DomEvent.disableClickPropagation(legend.getContainer());
    L.DomEvent.disableScrollPropagation(legend.getContainer());

    const tfiChk = document.getElementById('toggle-tfi');
    if (tfiChk) {
      tfiChk.addEventListener('change', function () {
        if (this.checked) {
          map_display.addLayer(tfiLayer);
        } else {
          map_display.removeLayer(tfiLayer);
        }
      });
    };


    // Prevent map dragging when clicking legend
  /*L.DomEvent.disableClickPropagation(document.querySelector('.map-legend'));

  document.querySelectorAll('.legend-item').forEach(item => {
      item.addEventListener('click', function () {
        const layerKey = this.dataset.layer;
        let layer;

        switch (layerKey) {
          case 'outside':
            layer = tfiOutsideLayer;
            break;
          case 'within':
            layer = tfiWithinLayer;
            break;
          case 'freq':
            layer = tfiFreqRiskLayer;
            break;
          case 'vulnerable':
            layer = tfiVulnerableLayer;
            break;
          case 'regen':
            layer = tfiRegeneratingLayer;
            break;
        }

        if (!layer) return;

        if (map.hasLayer(layer)) {
          map.removeLayer(layer);
          this.classList.add('legend-off');
        } else {
          map.addLayer(layer);
          this.classList.remove('legend-off');
        }
      });
    });*/


    const markersById = {};
      //  LIST OF BASEMAPS >> https://developers.arcgis.com/documentation/mapping-apis-and-services/maps/services/basemap-layer-service/#default-styles  //
      L.esri.Vector.vectorBasemapLayer("ArcGIS:Imagery", {
      //L.esri.Vector.vectorBasemapLayer("ArcGIS:StreetsNight", {
          apikey: apiKey,
          maxZoom: 19
      }).addTo(map_display);

      /*map_width = $("#modal-body").width();
      map_height = $("#modal-body").height();
      $("#map1").width(map_width).height(map_height);*/

      const rulerOptions = {
        position: 'topleft',
        strings: {          
            bearing: 'Bearing',
            length:  'Length',
            segment: 'Segment',
            finish:  'Finish',
        },        
        lengthUnit: {
          factor: 1000, 
          display: 'Metres',
          decimal: 3,
          label: 'Distance'
        }

      };

     L.control.ruler(rulerOptions).addTo(map_display);

      if (L.Control.Ruler && L.Control.Ruler.prototype) {
        L.Control.Ruler.prototype._getDistance = function (a, b) {
          return a.distanceTo(b); // <- correct meters
        };
      };

      featureCluster = L.markerClusterGroup({
        showCoverageOnHover: false,
        zoomToBoundsOnClick: false,
        spiderfyOnMaxZoom: true,
        spiderfyDistanceMultiplier: 1.4,
        maxClusterRadius: 40
      });
      featureCluster.on('clusterclick', function (e) {
        L.DomEvent.stop(e.originalEvent);
        e.layer.spiderfy();
      });
      featureCluster.addTo(map_display);
};

$(window).resize(function(){
  console.log("resize USED"); 
  if($('#ModalMap').is(':visible')==true){
    map_width = $("#modal-body").width();
    map_height = $("#modal-body").height();
    $("#map1").width(map_width).height(map_height);
  };
})

function clearFilters(){
  console.log("clearFilters USED")
  customColumns.forEach(function(item) {
   $('#'+item).val('')
  });
};

function createCustomSQL(value){
  console.log("createCustomSQL USED")
  for (let cs of customColumns) {
    var csVal=$('#'+cs).val();
    if(csVal){
     if(cs=="tfs_region" || cs=="fma_name" || cs=="agn_region" || cs=="bmpa_pri_comm"){
        customValue+=" AND "+cs+" = '"+csVal+"'";
     }else if (cs=='op_s_date' || cs=='op_e_date'){
        customValue+=" AND TO_CHAR("+cs+",'DD/MM/YYYY') iLIKE '%"+csVal+"%'";
     }else if (cs=='per_comp'){
        customValue+=" AND CAST("+cs+" AS VARCHAR(20)) iLIKE '%"+csVal+"%'";
     }else{
        customValue+=" AND "+cs+" iLIKE '%"+csVal+"%'";
     };
    };
  }
  return customValue;
};

/*! © SpryMedia Ltd - datatables.net/license */
!function(o){var n,i;"function"==typeof define&&define.amd?define(["jquery","datatables.net"],function(t){return o(t,window,document)}):"object"==typeof exports?(n=require("jquery"),i=function(t,e){e.fn.dataTable||require("datatables.net")(t,e)},"undefined"==typeof window?module.exports=function(t,e){return t=t||window,e=e||n(t),i(t,e),o(e,0,t.document)}:(i(window,n),module.exports=o(n,window,window.document))):o(jQuery,window,document)}(function(a,t,e,o){"use strict";function n(t){var e=this,o=t.table();this.s={dt:t,host:a(o.container()).parent(),header:a(o.header()),footer:a(o.footer()),body:a(o.body()),container:a(o.container()),table:a(o.node())};"static"===(o=this.s.host).css("position")&&o.css("position","relative"),t.on("draw.scrollResize",function(){e._size()}),t.on("destroy.scrollResize",function(){t.off(".scrollResize"),this.s.obj&&this.s.obj.remove()}.bind(this)),this._attach(),this._size();var o=t.settings()[0],n=(n=o.nScrollBody).scrollHeight>n.clientHeight;o.scrollBarVis&&!n&&t.columns.adjust()}var i=a.fn.dataTable;return n.prototype={_size:function(){var t=this.s,e=t.dt.table(),o=a(t.table).offset().top,n=t.host.height(),i=a("div.dataTables_scrollBody",e.container()),n=(n-=o)-(t.container.height()-(o+i.height()));a("div.dataTables_scrollBody",e.container()).css({maxHeight:n,height:n})},_attach:function(){var s=this,t=a("<iframe/>").css({position:"absolute",top:0,left:0,height:"100%",width:"100%",zIndex:-1,border:0}).attr("frameBorder","0").attr("src","about:blank");t[0].onload=function(){var t=this.contentDocument,o=t.body,n=o.offsetHeight,i=t;(i.defaultView||i.parentWindow).onresize=function(){var t=o.clientHeight||o.offsetHeight,e=i.documentElement.clientHeight;(t=!t&&e?e:t)!==n&&(n=t,s._size())}},t.appendTo(this.s.host).attr("data","about:blank"),this.s.obj=t}},i.ScrollResize=n,a(e).on("init.dt",function(t,e){"dt"===t.namespace&&(t=new i.Api(e),e.oInit.scrollResize||i.defaults.scrollResize)&&new n(t)}),i});

</script>
<!--****CUSTOM FUNCTIONS******-->
<script src="http://risk/BRU_Tools/NV/js/functions.js"></script>
<script src="http://risk/BRU_Tools/NV/js/Control.MiniMaps.js"></script>
<!--********************-->
</body>
</html>
