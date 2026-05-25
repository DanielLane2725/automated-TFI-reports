
<!DOCTYPE html>
<html lang="en-us"> 
<head>
 <title>BRU TFI Reporting</title>
 <meta content="text/html; charset=utf-8" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

<!-- DataTables Buttons (Bootstrap 5 styling) -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

<!-- Export deps -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Buttons export types -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<link rel="stylesheet" href="../css/tfi-datatables.css">

<!-- To Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<!-- message box -->
<script src="https://cdn.jsdelivr.net/npm/bootbox@6.0.4/dist/bootbox.min.js"></script>

</head>
<body>
<p></p>

<!-- The Table -->
<div class="table-wrap">
    <table id="tfiTable" class="display compact stripe" style="width:100%">        
        <thead>
        <tr>
            <th></th>
            <th>ID</th>
            <th>Vegetation Code</th>
            <th>Vegetation Group</th>
            <th>TFI Category</th>
            <th>FRB Treat</th>
            <th>TFI Area (ha)</th>
            <th>Total Veg Area (ha)</th>
            <th>TFI %</th>
        </tr>
        <tr class="filters">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
    </table>
</div>
    
<script>

const bioregionCache = {};
const fmaCache = {};
const lgaCache = {};
const tfsregionCache = {};


$(document).ready(function () {
    const dropdownCols = [2, 3, 4, 5];    
    let lastVegCode = null;
    let groupIndex = 0;

    const table = $('#tfiTable').DataTable({
        ajax: {
            url: '../api/tfi_tasveg_statewide.php',
            dataSrc: 'data'
        },
        processing: true,
        deferRender: true,        
        lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All']
            ],
        pageLength: 25,        
        order: [[2, 'asc'], [4, 'asc']],
        orderCellsTop: true,
        fixedHeader: false,
        dom: "<'dt-top'<'dt-left'l><'dt-center'B><'dt-right'f>>rtip",
        buttons: [{
                extend: 'excelHtml5',
                text: 'Export Table - Excel',
                className: 'dt-clear-filters', 
                exportOptions: {
                    columns: ':visible:not(:first-child)',
                    modifier: { search: 'applied', order: 'applied'}//, page: 'current' }
                }}
        ],
        columnDefs: [
            {
                targets: 0,
                width: '600px',
                orderable: false,
                className: 'dt-center report-col'
            },
        ],
        columns: [           
            {
                data: null,
                orderable: false,
                className: 'dt-center report-col',
                render: function (data, type, row) {
                    return `                
                        <div class="row-action-wrap">
                            <button
                                class="row-report-btn"
                                data-id="${row.objectid}">
                                Create report
                            </button>

                            <select class="row-bioregion-select" 
                                    style="width:140px"
                                    data-veg="${row.vegetationcode}"
                                    data-tfi="${row.tfi_cat}"
                                    data-obid="${row.objectid}">
                            </select>

                            <select class="row-fma-select"
                                    style="width:100px"
                                    data-veg="${row.vegetationcode}"
                                    data-tfi="${row.tfi_cat}"
                                    data-obid="${row.objectid}">                                
                            </select>

                            <select class="row-lga-select"
                                    style="width:100px"
                                    data-veg="${row.vegetationcode}"
                                    data-tfi="${row.tfi_cat}"
                                    data-obid="${row.objectid}">
                            </select>

                            <select class="row-tfsregion-select"
                                    style="width:100px"
                                    data-veg="${row.vegetationcode}"
                                    data-tfi="${row.tfi_cat}"
                                    data-obid="${row.objectid}">
                            </select>

                        </div>                                                               
                    `;
                }
                        
            },
            { data: 'objectid', visible: false },
            { data: 'vegetationcode' },
            { data: 'vegetationgroup' },
            { data: 'tfi_cat' },
            { data: 'frb_treat' },
            { data: 'tfi_area_ha', render: $.fn.dataTable.render.number(',', '.', 2) },
            { data: 'tfi_total_area_ha', render: $.fn.dataTable.render.number(',', '.', 2) },
            {
                data: 'tfi_per',
                render: function (d) {
                    return d + ' %';
                }
            }
        ],      
        createdRow: function (row) {
                const $selectB = $(row).find('.row-bioregion-select');

                if ($selectB.data('select2')) return;                
                    $selectB.select2({
                        placeholder: 'Bioregion',
                        allowClear: true,
                        width: 'auto',
                        dropdownAutoWidth: true,
                        dropdownParent: $(row)
                    });
                   
                    /*$selectB.select2({
                        placeholder: 'Bioregion',
                        allowClear: true,
                        closeOnSelect: false,
                        width: 'style',
                        dropdownAutoWidth: true,
                        dropdownParent: $(row)
                    });*/

                const $selectF = $(row).find('.row-fma-select');

                if ($selectF.data('select2')) return;                
                    $selectF.select2({ 
                        placeholder: 'FMA',
                        allowClear: true,
                        width: 'auto',
                        dropdownAutoWidth: true,
                        dropdownParent: $(row)
                    });

                const $selectL = $(row).find('.row-lga-select');

                if ($selectL.data('select2')) return;                
                    $selectL.select2({
                        placeholder: 'LGA',
                        allowClear: true,
                        width: 'auto',
                        dropdownAutoWidth: true,
                        dropdownParent: $(row)
                    });

                const $selectR = $(row).find('.row-tfsregion-select');

                if ($selectR.data('select2')) return;                
                    $selectR.select2({
                        placeholder: 'TFS Region',
                        allowClear: true,
                        width: 'auto',
                        dropdownAutoWidth: true,
                        dropdownParent: $(row)
                    });
        },
        initComplete: function () {
            const api = this.api();
            const $header = $(api.table().header());
            const $filterRow = $header.find('tr.filters');
            dropdownCols.forEach(function (colIdx) {
                const column = api.column(colIdx);
                const visibleIdx = column.index('visible');
                const cell = $filterRow.find('th').eq(visibleIdx);

                if (!cell.length) return;

                const $select = $('<select style="width:100%"><option value=""></option></select>')
                .appendTo(cell.empty());
                column.data().unique().sort().each(function (d) {
                    if (d !== null && d !== undefined && d !== '') {
                        $select.append(new Option(d, d));
                    }
                });
                if ($.fn.select2) {
                    $select.select2({
                        placeholder: 'All',
                        allowClear: true,
                        width: '100%'
                    });
                }
                // filter
                $select.on('change', function () {
                    const val = $.fn.dataTable.util.escapeRegex($(this).val());
                    column.search(val ? `^${val}$` : '', true, false).draw();
                });
            });
        }, 
        // THIS IS FOR A LINE OR BORDER TO SEPERATE VEG CODE LEVELS IN THE TABLE //
        /*drawCallback: function () {
            const api = this.api();
            const rows = api.rows({ page: 'current' }).nodes();
            const data = api.rows({ page: 'current' }).data();
            let lastVegCode = null;
            for (let i = 0; i < data.length; i++) {
                const currentVegCode = data[i].vegetationcode; // OR data[i][<index>]
                // remove old class (important when filtering)
                $(rows[i]).removeClass('veg-group-start');
                if (currentVegCode !== lastVegCode) {
                    $(rows[i]).addClass('veg-group-start');
                }
                lastVegCode = currentVegCode;
            }
        }*/
        // ************************************************************************/
        drawCallback: function () {

    const api = this.api();
    const rows = api.rows({ page: 'current' }).nodes();
    const data = api.rows({ page: 'current' }).data();

    let lastVegCode = null;

    // clear all classes first
    $(rows).removeClass('veg-group-start veg-group veg-group-end');

    for (let i = 0; i < data.length; i++) {

        const currentVegCode = data[i].vegetationcode;

        // always mark row as part of group
        $(rows[i]).addClass('veg-group');

        // start of group
        if (currentVegCode !== lastVegCode) {
            $(rows[i]).addClass('veg-group-start');
        }

        // end of group (look ahead)
        if (i === data.length - 1 || data[i + 1].vegetationcode !== currentVegCode) {
            $(rows[i]).addClass('veg-group-end');
        }

        lastVegCode = currentVegCode;
    }
}
    });

    $('.dt-center #clearFilters').remove();

    const $clearBtn = $('<button>', {
    id: 'clearFilters',
    class: 'dt-clear-filters btn btn-secondary',
    text: 'Clear all filters'
    }).appendTo('.dt-top .dt-center');

    $clearBtn.on('click', function () {
        // Clear DataTables column searches
        table.columns().search('');
        // Clear Select2 dropdowns
        $('#tfiTable thead tr.filters select').each(function () {
            $(this).val(null).trigger('change.select2');
        });
        $('#tfiTable .close-subtable').trigger('click');
        table.draw();
    });

    $('#tfiTable tbody').on('click', '.row-report-btn', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        if (!id) return;
        const wb = XLSX.utils.book_new();
        // SUMMARY SHEET (first tab): extract the parent DataTables row
        const $tr = $(this).closest('tr');
        const dtRow = table.row($tr);
        if (!dtRow || dtRow.length === 0) return;

        const rowIdx = dtRow.index();
        // Build headers + values from VISIBLE columns (matches what user sees)
        const headers = [];
        const values  = [];        
        table.columns(':visible').every(function () {
            const colIdx = this.index();
            const headerText = stripHtml($(this.header()).text()).trim();
            // ✅ skip button column by name
            if (headerText.toLowerCase().includes('create report') || headerText === '') {
                return;
            }
            headers.push(headerText);
            let v;
            try {
                v = table.cell(rowIdx, colIdx).render('display');
            } catch (err) {
                v = table.cell(rowIdx, colIdx).data();
            }
            values.push(stripHtml(v));
        });
        //header row + data row
        const wsSummary = XLSX.utils.aoa_to_sheet([headers, values]);
        applyNumberFormat(wsSummary);
        let tfiPctCol = -1;
        for (let iCol = 0; iCol < headers.length; iCol++) {
            if (headers[iCol].trim().toLowerCase() === 'tfi %') {
                tfiPctCol = iCol;
                break;
            }
        }
        if (tfiPctCol !== -1) {
            const addr = XLSX.utils.encode_cell({ r: 1, c: tfiPctCol });
            const cell = wsSummary[addr];
            if (cell && cell.v != null && cell.v !== '') {
                const raw = cell.v.toString().trim();
                let v = parseFloat(raw);
                if (!Number.isNaN(v)) {
                    if (raw.includes('%')) v = v / 100; // "15.14%" -> 0.1514
                    cell.t = 'n';
                    cell.v = v;
                    cell.z = '0.00%';
                }
            }
        }
        wsSummary['!cols'] = headers.map((h, i) => {
            const headerLength = (h || '').toString().length;
            const valueLength = (values[i] || '').toString().length;
            return {
                wch: Math.min(Math.max(headerLength, valueLength, 12), 50)
            };

        });
        // Append FIRST so it becomes the first worksheet tab
        XLSX.utils.book_append_sheet(wb, wsSummary, 'Summary');
        //EXISTING REPORT SHEETS (only if visible in DOM)

        const $matches = $('tr[id^="row-' + id + '-"]');
        
        function runExport(matches) {

            matches.each(function () {
            const $row = $(this);
            const $tables = $row.find('.row-subtables table');
            if (!$tables.length) return;

            let sheetNameBase = $row.attr('id')
                .replace('row-' + id + '-', '')
                .substring(0, 31);

            $tables.each(function (i) {
                const $table = $(this).clone(true);
                $table.find('button').remove();

                const ws = XLSX.utils.table_to_sheet($table[0]);

                applyNumberFormat(ws);
                ws['!cols'] = getColWidths($table);

                const pctCol = findTfiPercentColumn(ws);
                applyPercentFormat(ws, pctCol);

                let sheetName = sheetNameBase;
                if ($tables.length > 1) {
                    sheetName = (sheetNameBase + '_' + (i + 1)).substring(0, 31);
                }

                XLSX.utils.book_append_sheet(wb, ws, sheetName);
            });
        });
        
            // filename
            const rowData = dtRow.data();
            const veg = (rowData.vegetationcode || 'veg').toString().replace(/[^a-z0-9]/gi, '_');
            const tfi = (rowData.tfi_cat || 'tfi').toString().replace(/[^a-z0-9]/gi, '_');
            const filename = `report_${veg}_${tfi}_${id}.xlsx`;

            XLSX.writeFile(wb, filename);
        }

        // ✅ only prompt when there are NO subreports
        if (!$matches.length) {
            bootbox.confirm({
                title: "Export Report",
                message: "No sub-report tables are open.<br><br>Continue with <b>summary only</b> export?",
                buttons: {
                    confirm: { label: "✅ Yes", className: "btn-success" },
                    cancel:  { label: "❌ No",  className: "btn-secondary" }
                },
                callback: function (result) {
                    if (!result) return;       // user said No
                    runExport($matches);        // will export summary only (matches is empty)
                }
            });

            return; // IMPORTANT: stop here, wait for callback
        }

        // ✅ if subreports exist, no prompt, just export everything
        runExport($matches);
    });

    $('#tfiTable').on('select2:opening', '.row-bioregion-select', function (e) {
        const $select = $(this);
        // already loaded? let it open normally
        if ($select.data('loaded')) return;
        // stop Select2 opening with empty options
        e.preventDefault();
        const veg = $select.data('veg');
        const tfi = $select.data('tfi');
        const key = `${veg}|${tfi}`;
        // cached?
        if (bioregionCache[key]) {
            populateOptions($select, bioregionCache[key]);
            // now open for real
            $select.select2('open');
            return;
        }
        $.getJSON('../api/tfi_tasveg_bioregion.php', { veg, tfi })
            .done(function (resp) {
                const rows = resp && resp.data ? resp.data : [];
                const regions = [...new Set(rows.map(r => r.bioregion).filter(Boolean))];
                bioregionCache[key] = regions;
                populateOptions($select, regions);
                $select.select2('open');
            });
    });

    $('#tfiTable').on('change', '.row-bioregion-select', function () {
        const $select = $(this);
        const tr = $select.closest('tr');
        const veg = $select.data('veg');
        const tfi = $select.data('tfi');
        const btnid = $select.data('obid');
        const bioregion = $select.val();
        //alert($select.data('obid'));

        if (!bioregion) return;

        // reset select2 back to placeholder
        const origWidth = $select.data('orig-width');
        $select.val(null).trigger('change.select2');
        if (origWidth) {
            $select.closest('.select2').css('width', origWidth + 'px');
        }

        //const tr = $btn.closest('tr');
        const $subRow = ensureRowSubtables(tr,'bioregion', btnid);
        const $sub = $subRow.find('.bioregion-subtbl');
        $sub
        .html('Loading…')
        .slideDown(150);

        $.getJSON('../api/tfi_tasveg_bioregion.php', {
            veg,
            tfi,
            bioregion: bioregion === 'ALL' ? null : bioregion,
        })
        .done(function (resp) {         
            //$sub.html(renderBioregionSubtable(resp.data, resp.data[0].objectid));
            $sub.html(renderBioregionSubtable(resp.data, btnid, veg));
        })
        .fail(function () {
            $sub.html('<div class="subtable-error">Failed to load bioregion data.</div>');
        });
    });

    $('#tfiTable').on('select2:select', '.row-bioregion-select', function () {
        const $select = $(this);
        const baseWidth = $select.data('baseWidth');
        if (!baseWidth) return;
        const $container = $select.next('.select2-container');
        // snap back to original width
        $container.css('width', baseWidth + 'px');
    });

    /*function ensureRowSubtables(tr,c,i) {
        let $subRow = tr.nextAll('.row-'+i+'-subtbl');
        if ($subRow.length) {
            return $subRow;
        }
        if ($subRow.length) {
            return $subRow;
        }

        // create + insert
        $subRow = $(`
            <tr id="row-`+i+`" class="row-`+c+`-subtbl">
                <td colspan="100%">
                    <div class="row-subtables">
                        <div class="`+c+`-subtbl"></div>
                    </div>
                </td>
            </tr>
        `);

        tr.after($subRow);
        return $subRow;
    }*/

    function ensureRowSubtables(tr, c, i) {
        let $subRow = $('#row-'+i+"-"+c);
        if ($subRow.length) return $subRow;

        $subRow = $(`<tr id="row-${i}-${c}" class="row-${c}-subtbl">
        <td colspan="100%">
                    <div class="row-subtables">
                        <div class="`+c+`-subtbl"></div>
                    </div>
                </td>
        </tr>`);
        tr.after($subRow);
        return $subRow;
    }

    $('#tfiTable').on('select2:close', '.row-bioregion-select', function () {
        const $select = $(this);
        const baseWidth = $select.data('baseWidth');
        if (!baseWidth) return;
        $select.next('.select2-container')
            .css('width', baseWidth + 'px');
    });

    $('#tfiTable').on('select2:close', '.row-fma-select', function () {
        const $select = $(this);
        const baseWidth = $select.data('baseWidth');
        if (!baseWidth) return;
        $select.next('.select2-container')
            .css('width', baseWidth + 'px');
    });

    $('#tfiTable').on('select2:close', '.row-lga-select', function () {
        const $select = $(this);
        const baseWidth = $select.data('baseWidth');
        if (!baseWidth) return;
        $select.next('.select2-container')
            .css('width', baseWidth + 'px');
    });

    $('#tfiTable').on('select2:close', '.row-tfsregion-select', function () {
        const $select = $(this);
        const baseWidth = $select.data('baseWidth');
        if (!baseWidth) return;
        $select.next('.select2-container')
            .css('width', baseWidth + 'px');
    });

    $('#tfiTable').on('select2:opening', '.row-fma-select', function (e) {
        const $select = $(this);
        // already loaded? let it open normally
        if ($select.data('loaded')) return;
        // stop Select2 opening with empty options
        e.preventDefault();
        const veg = $select.data('veg');
        const tfi = $select.data('tfi');
        const key = `${veg}|${tfi}`;
        // cached?
        if (fmaCache[key]) {
            populateOptionsFMA($select, fmaCache[key]);
            // now open for real
            $select.select2('open');
            return;
        }
        $.getJSON('../api/tfi_tasveg_fma.php', { veg, tfi })
            .done(function (resp) {
                const rows = resp && resp.data ? resp.data : [];
                const fma = [...new Set(rows.map(r => r.fma).filter(Boolean))];
                fmaCache[key] = fma;
                populateOptionsFMA($select, fma);
                $select.select2('open');
            });
    });

    $('#tfiTable').on('select2:opening', '.row-lga-select', function (e) {
        const $select = $(this);
        if ($select.data('loaded')) return;
        e.preventDefault();
        const veg = $select.data('veg');
        const tfi = $select.data('tfi');
        const key = `${veg}|${tfi}`;
        if (lgaCache[key]) {
            populateOptionsLGA($select, lgaCache[key]);
            $select.select2('open');
            return;
        }

        $.getJSON('../api/tfi_tasveg_lga.php', { veg, tfi })
            .done(function (resp) {
                const rows = resp && resp.data ? resp.data : [];
                const lga = [...new Set(rows.map(r => r.lga).filter(Boolean))];
                lgaCache[key] = lga;
                populateOptionsLGA($select, lga);
                $select.select2('open');
        });

        /*$.getJSON('../api/tfi_tasveg_lga.php', { veg, tfi })
        .done(function (resp) {
            console.log('Response:', resp);
            // Handle unexpected/empty response
            if (!resp || !resp.data) {
                console.warn('No data returned from API');
                populateOptionsLGA($select, []);   // empty fallback
                return;
            }
            const rows = resp.data;
            const lga = [...new Set(rows.map(r => r.lga).filter(Boolean))];
            lgaCache[key] = lga;
            populateOptionsLGA($select, lga);
            $select.select2('open');
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.error('API request failed:', textStatus, errorThrown);
            // Optional user feedback
            alert('Failed to load LGA options. Please try again.');
            // Safe fallback
            populateOptionsLGA($select, []);
        });*/
    });

    $('#tfiTable').on('select2:opening', '.row-tfsregion-select', function (e) {
        const $select = $(this);
        // already loaded? let it open normally
        if ($select.data('loaded')) return;
        // stop Select2 opening with empty options
        e.preventDefault();
        const veg = $select.data('veg');
        const tfi = $select.data('tfi');
        const key = `${veg}|${tfi}`;
        // cached?
        if (tfsregionCache[key]) {
            populateOptionsTfsRegions($select, tfsregionCache[key]);
            // now open for real
            $select.select2('open');
            return;
        }
        $.getJSON('../api/tfi_tasveg_tfsregion.php', { veg, tfi })
            .done(function (resp) {
                const rows = resp && resp.data ? resp.data : [];
                const tfsregion = [...new Set(rows.map(r => r.tfsregion).filter(Boolean))];
                tfsregionCache[key] = tfsregion;
                populateOptionsTfsRegions($select, tfsregion);
                $select.select2('open');
            });
    });

    $('#tfiTable').on('change', '.row-fma-select', function () {
        const $select = $(this);
        const tr = $select.closest('tr');
        const veg = $select.data('veg');
        const tfi = $select.data('tfi');
        const btnid = $select.data('obid');
        const fma = $select.val();

        if (!fma) return;

        // reset select2 back to placeholder
        const origWidth = $select.data('orig-width');
        $select.val(null).trigger('change.select2');
        if (origWidth) {
            $select.closest('.select2').css('width', origWidth + 'px');
        }

        //const tr = $btn.closest('tr');
        const $subRow = ensureRowSubtables(tr,'fma', $select.data('obid'));
        const $sub = $subRow.find('.fma-subtbl');
        $sub
        .html('Loading…')
        .slideDown(150);

        $.getJSON('../api/tfi_tasveg_fma.php', {
            veg,
            tfi,
            fma: fma === 'ALL' ? null : fma
        })
        .done(function (resp) {
            //$sub.html(renderFMASubtable(resp.data));
            $sub.html(renderFMASubtable(resp.data, btnid, veg));
        })
        .fail(function () {
            $sub.html('<div class="subtable-error">Failed to load fma data.</div>');
        });
    });

    $('#tfiTable').on('change', '.row-lga-select', function () {
        const $select = $(this);
        const tr = $select.closest('tr');
        const veg = $select.data('veg');
        const tfi = $select.data('tfi');
        const btnid = $select.data('obid');
        const lga = $select.val();

        if (!lga) return;

        // reset select2 back to placeholder
        const origWidth = $select.data('orig-width');
        $select.val(null).trigger('change.select2');
        if (origWidth) {
            $select.closest('.select2').css('width', origWidth + 'px');
        }

        //const tr = $btn.closest('tr');
        const $subRow = ensureRowSubtables(tr,'lga', $select.data('obid'));
        const $sub = $subRow.find('.lga-subtbl');
        $sub
        .html('Loading…')
        .slideDown(150);

        $.getJSON('../api/tfi_tasveg_lga.php', {
            veg,
            tfi,
            lga: lga === 'ALL' ? null : lga
        })
        .done(function (resp) {
            $sub.html(renderLGASubtable(resp.data, btnid, veg));
        })
        .fail(function () {
            $sub.html('<div class="subtable-error">Failed to load lga data.</div>');
        });
    });

    $('#tfiTable').on('change', '.row-tfsregion-select', function () {
        const $select = $(this);
        const tr = $select.closest('tr');
        const veg = $select.data('veg');
        const tfi = $select.data('tfi');
        const btnid = $select.data('obid');
        const tfsregion = $select.val();

        if (!tfsregion) return;

        // reset select2 back to placeholder
        const origWidth = $select.data('orig-width');
        $select.val(null).trigger('change.select2');
        if (origWidth) {
            $select.closest('.select2').css('width', origWidth + 'px');
        }

        //const tr = $btn.closest('tr');
        const $subRow = ensureRowSubtables(tr,'tfsregion', $select.data('obid'));
        const $sub = $subRow.find('.tfsregion-subtbl');
        $sub
        .html('Loading…')
        .slideDown(150);

        $.getJSON('../api/tfi_tasveg_tfsregion.php', {
            veg,
            tfi,
            tfsregion: tfsregion === 'ALL' ? null : tfsregion
        })
        .done(function (resp) {
            $sub.html(renderTfsregionSubtable(resp.data, btnid, veg));
        })
        .fail(function () {
            $sub.html('<div class="subtable-error">Failed to load tfsregion data.</div>');
        });
    });

    $('#tfiTable').on('select2:select', '.row-fma-select', function () {
        const $select = $(this);
        const baseWidth = $select.data('baseWidth');
        if (!baseWidth) return;
        const $container = $select.next('.select2-container');
        // snap back to original width
        $container.css('width', baseWidth + 'px');
    });

    $('#tfiTable').on('select2:select', '.row-lga-select', function () {
        const $select = $(this);
        const baseWidth = $select.data('baseWidth');
        if (!baseWidth) return;
        const $container = $select.next('.select2-container');
        // snap back to original width
        $container.css('width', baseWidth + 'px');
    });

    $('#tfiTable').on('select2:select', '.row-tfsregion-select', function () {
        const $select = $(this);
        const baseWidth = $select.data('baseWidth');
        if (!baseWidth) return;
        const $container = $select.next('.select2-container');
        // snap back to original width
        $container.css('width', baseWidth + 'px');
    });

    function populateOptions($select, regions) {
        $select.empty();
        $select.append('<option value=""></option>'); // placeholder for Select2
        // optional "ALL"
        $select.append('<option value="ALL">All bioregions</option>');
        regions.forEach(r => {
            $select.append(`<option value="${r}">${r}</option>`);
        });
        $select.data('loaded', true);
        // tell select2 its underlying options changed
        $select.trigger('change.select2');        
        // autosize AFTER options exist
        autosizeSelect2($select);
    }

    /*function populateOptions($select, regions) {
        const selected = $select.val() || [];  // array for multi-select
        $select.empty();
        // optional empty option helps allowClear/placeholder behaviour
        $select.append(new Option('', '', false, false));
        regions.forEach(r => {
            const isSel = selected.includes(r);
            $select.append(new Option(r, r, false, isSel));
        });
        $select.data('loaded', true);
        // tell select2 to refresh options
        $select.trigger('change.select2');
    }*/

    function populateOptionsFMA($select, regions) {
        $select.empty();
        $select.append('<option value=""></option>'); // placeholder for Select2
        // optional "ALL"
        $select.append('<option value="ALL">All FMAs</option>');
        regions.forEach(r => {
            $select.append(`<option value="${r}">${r}</option>`);
        });
        $select.data('loaded', true);
        // tell select2 its underlying options changed
        $select.trigger('change.select2');        
        // autosize AFTER options exist
        autosizeSelect2($select);
    }

    function populateOptionsLGA($select, regions) {
        $select.empty();
        $select.append('<option value=""></option>'); // placeholder for Select2
        // optional "ALL"
        $select.append('<option value="ALL">All LGAs</option>');
        regions.forEach(r => {
            $select.append(`<option value="${r}">${r}</option>`);
        });
        $select.data('loaded', true);
        // tell select2 its underlying options changed
        $select.trigger('change.select2');        
        // autosize AFTER options exist
        autosizeSelect2($select);
    }

    function populateOptionsTfsRegions($select, regions) {
        $select.empty();
        $select.append('<option value=""></option>'); // placeholder for Select2
        // optional "ALL"
        $select.append('<option value="ALL">All TFS Regionss</option>');
        regions.forEach(r => {
            $select.append(`<option value="${r}">${r}</option>`);
        });
        $select.data('loaded', true);
        // tell select2 its underlying options changed
        $select.trigger('change.select2');        
        // autosize AFTER options exist
        autosizeSelect2($select);
    }

    function measureTextWidth(text, font) {
        const canvas = measureTextWidth.canvas ||
            (measureTextWidth.canvas = document.createElement("canvas"));
        const context = canvas.getContext("2d");
        context.font = font;
        return context.measureText(text).width;
    }

    function autosizeSelect2($select) {
        const $container = $select.next('.select2-container');
        // store base width once
        storeBaseWidth($select);
        const font = window.getComputedStyle(
            $container.find('.select2-selection__rendered')[0]
        ).font;
        let maxWidth = 0;
        $select.find('option').each(function () {
            const text = $(this).text();
            if (!text) return;
            maxWidth = Math.max(maxWidth, measureTextWidth(text, font));
        });
        const finalWidth = Math.ceil(maxWidth + 60);
        $container.css('width', finalWidth + 'px');
    }

    function storeBaseWidth($select) {
        if ($select.data('baseWidth')) return;
        const $container = $select.next('.select2-container');
        $select.data('baseWidth', $container.outerWidth());
    }

    function renderBioregionSubtable(rows, j, v) {
        if (!rows || !rows.length) {
            return `<div class="subtable-empty">No bioregion rows found.</div>`;
        }
        let html = `
            <table class="tv-subtable">
                <thead>
                    <tr>                    
                        <th class="tv-header">
                        <button id="`+j+`-btn-bioregion" class="close-subtable btn btn-secondary">Close</button>
                        <span>Bioregion</span>
                        </th>
                        <th>Vegetation Code</th>
                        <th>TFI Category</th>
                        <th>TFI Area (ha)</th>
                        <th>Total ${v} Area (ha)</th>
                        <th>TFI %</th>
                    </tr>
                </thead>
                <tbody>
        `;
        let totalTfiArea = 0;
        let totalArea = 0;
  
        rows.forEach(r => {
            const tfiArea = Number(r.area_ha) || 0;
            const totArea = Number(r.total_area_ha) || 0;

            totalTfiArea += tfiArea;
            totalArea += totArea;

            html += `
                <tr>
                    <td>${r.bioregion}</td>
                    <td>${r.vegetationcode}</td>
                    <td>${r.tfi_cat}</td>
                    <td>${tfiArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</td>
                    <td>${totArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</td>
                    <td>${Number(r.tfi_per).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})} %</td>
                </tr>
            `;
        });

        html += `
                <tr class="tv-total-row">
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong>${totalTfiArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</strong></td>
                    <td><strong>${totalArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</strong></td>
                    <td></td>
                </tr>
                `;

        html += `
                </tbody>
            </table>
        `;
        return html;
        /*rows.forEach(r => {
            html += `
                <tr>
                    <td>${r.bioregion}</td>
                    <td>${r.vegetationcode}</td>
                    <td>${r.tfi_cat}</td>
                    <td>${Number(r.area_ha).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</td>
                    <td>${Number(r.total_area_ha).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</td>
                    <td>${Number(r.tfi_per).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})} %</td>
                </tr>
            `;
        });
        html += `
                </tbody>
            </table>
        `;
        return html;*/
    }

    function renderFMASubtable(rows, j, v) {
        if (!rows || !rows.length) {
            return `<div class="subtable-empty">No fma rows found.</div>`;
        }
        let html = `
            <table class="tv-subtable">
                <thead>
                    <tr>                    
                        <th class="tv-header">
                        <button id="`+j+`-btn-fma" class="close-subtable btn btn-secondary">Close</button>
                        <span>FMA</span>
                        </th>
                        <th>Vegetation Code</th>
                        <th>TFI Category</th>
                        <th>TFI Area (ha)</th>
                        <th>Total ${v} Area (ha)</th>
                        <th>TFI %</th>
                    </tr>
                </thead>
                <tbody>
        `;

        let totalTfiArea = 0;
        let totalArea = 0;
  
        rows.forEach(r => {
            const tfiArea = Number(r.area_ha) || 0;
            const totArea = Number(r.total_area_ha) || 0;

            totalTfiArea += tfiArea;
            totalArea += totArea;

            html += `
                <tr>
                    <td>${r.fma}</td>
                    <td>${r.vegetationcode}</td>
                    <td>${r.tfi_cat}</td>
                    <td>${tfiArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</td>
                    <td>${totArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</td>
                    <td>${Number(r.tfi_per).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})} %</td>
                </tr>
            `;
        });

        html += `
                <tr class="tv-total-row">
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong>${totalTfiArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</strong></td>
                    <td><strong>${totalArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</strong></td>
                    <td></td>
                </tr>
                `;

        html += `
                </tbody>
            </table>
        `;
        return html;
        /*rows.forEach(r => {
            html += `
                <tr>
                    <td>${r.fma}</td>
                    <td>${r.vegetationcode}</td>
                    <td>${r.tfi_cat}</td>
                    <td>${Number(r.area_ha).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</td>
                    <td>${Number(r.total_area_ha).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</td>
                    <td>${Number(r.tfi_per).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})} %</td>
                </tr>
            `;
        });
        html += `
                </tbody>
            </table>
        `;
        return html;*/
    }

    function renderLGASubtable(rows, j, v) {
        if (!rows || !rows.length) {
            return `<div class="subtable-empty">No lga rows found.</div>`;
        }
        let html = `
            <table class="tv-subtable">
                <thead>
                    <tr>                    
                        <th class="tv-header">
                        <button id="`+j+`-btn-lga" class="close-subtable btn btn-secondary">Close</button>
                        <span>LGA</span>
                        </th>
                        <th>Vegetation Code</th>
                        <th>TFI Category</th>
                        <th>TFI Area (ha)</th>
                        <th>Total ${v} Area (ha)</th>
                        <th>TFI %</th>
                    </tr>
                </thead>
                <tbody>
        `;

        let totalTfiArea = 0;
        let totalArea = 0;
  
        rows.forEach(r => {
            const tfiArea = Number(r.area_ha) || 0;
            const totArea = Number(r.total_area_ha) || 0;

            totalTfiArea += tfiArea;
            totalArea += totArea;

            html += `
                <tr>
                    <td>${r.lga}</td>
                    <td>${r.vegetationcode}</td>
                    <td>${r.tfi_cat}</td>
                    <td>${tfiArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</td>
                    <td>${totArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</td>
                    <td>${Number(r.tfi_per).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})} %</td>
                </tr>
            `;
        });

        html += `
                <tr class="tv-total-row">
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong>${totalTfiArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</strong></td>
                    <td><strong>${totalArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</strong></td>
                    <td></td>
                </tr>
                `;

        html += `
                </tbody>
            </table>
        `;
        return html;
        /*rows.forEach(r => {
            html += `
                <tr>
                    <td>${r.lga}</td>
                    <td>${r.vegetationcode}</td>
                    <td>${r.tfi_cat}</td>
                    <td>${Number(r.area_ha).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</td>
                    <td>${Number(r.total_area_ha).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</td>
                    <td>${Number(r.tfi_per).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})} %</td>
                </tr>
            `;
        });
        html += `
                </tbody>
            </table>
        `;
        return html;*/
    }

    function renderTfsregionSubtable(rows, j, v) {
        if (!rows || !rows.length) {
            return `<div class="subtable-empty">No tfs region rows found.</div>`;
        }
        let html = `
            <table class="tv-subtable">
                <thead>
                    <tr>                    
                        <th class="tv-header">
                        <button id="`+j+`-btn-tfsregion" class="close-subtable btn btn-secondary">Close</button>
                        <span>TFS Region</span>
                        </th>
                        <th>Vegetation Code</th>
                        <th>TFI Category</th>
                        <th>TFI Area (ha)</th>
                        <th>Total ${v} Area (ha)</th>
                        <th>TFI %</th>
                    </tr>
                </thead>
                <tbody>
        `;

        let totalTfiArea = 0;
        let totalArea = 0;
  
        rows.forEach(r => {
            const tfiArea = Number(r.area_ha) || 0;
            const totArea = Number(r.total_area_ha) || 0;

            totalTfiArea += tfiArea;
            totalArea += totArea;

            html += `
                <tr>
                    <td>${r.tfsregion}</td>
                    <td>${r.vegetationcode}</td>
                    <td>${r.tfi_cat}</td>
                    <td>${tfiArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</td>
                    <td>${totArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</td>
                    <td>${Number(r.tfi_per).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})} %</td>
                </tr>
            `;
        });

        html += `
                <tr class="tv-total-row">
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong>${totalTfiArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</strong></td>
                    <td><strong>${totalArea.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}</strong></td>
                    <td></td>
                </tr>
                `;

        html += `
                </tbody>
            </table>
        `;
        return html;
    }

    
    $('#tfiTable').on('click', '.close-subtable', function () {
        removeSubTbl(this)
    });
    
    function removeSubTbl(btn) {
        const $idPart = btn.id.split('-')[0];
        const $fPart = btn.id.split('-')[2];
        const $subRow = $('#row-' + $idPart+'-'+$fPart);
        const $parentTr = $subRow.prev('tr');
        const $sub = $subRow.find('.'+$fPart+'-subtbl');
        $sub.slideUp(150, function () {
            $sub.empty();
            $subRow.remove();
        });
        $parentTr.removeClass('shown');
    }

});

function stripHtml(x) {
    if (x == null) return '';
    // DataTables cell data can include HTML
    return x.toString()
        .replace(/<br\s*\/?>/gi, '\n')
        .replace(/<[^>]*>/g, '')
        .replace(/&nbsp;/g, ' ')
        .trim();
}

function getColWidths($table) {
    const widths = [];
    $table.find('tr').each(function () {
        $(this).find('th, td').each(function (i) {
            const len = $(this).text().trim().length;
            widths[i] = Math.max(widths[i] || 10, len);
        });
    });
    return widths.map(w => ({ wch: Math.min(w, 40) }));
}

function findTfiPercentColumn(ws) {
    if (!ws || !ws['!ref']) return -1;
    const range = XLSX.utils.decode_range(ws['!ref']);
    for (let C = range.s.c; C <= range.e.c; C++) {
        const addr = XLSX.utils.encode_cell({ r: 0, c: C });
        const cell = ws[addr];
        if (!cell || cell.v == null) continue;

        const header = cell.v.toString().trim().toLowerCase();
        if (/^tfi\s*%$/.test(header)) return C; // EXACT "TFI %"
    }
    return -1;
}

function applyPercentFormat(ws, percentCol) {
    if (percentCol === -1 || !ws || !ws['!ref']) return;
    const range = XLSX.utils.decode_range(ws['!ref']);
    for (let R = 1; R <= range.e.r; R++) {
        const addr = XLSX.utils.encode_cell({ r: R, c: percentCol });
        const cell = ws[addr];
        if (!cell || cell.v == null || cell.v === '') continue;
        const raw = cell.v.toString().trim();
        // remove % then parse
        let v = toNumber(raw.replace('%', ''));
        if (Number.isNaN(v)) continue;
        // if it was "11.66%" convert to 0.1166
        if (raw.includes('%')) v = v / 100;

        cell.t = 'n';
        cell.v = v;
        cell.z = '0.00%';
    }
}

function applyNumberFormat(ws) {
    if (!ws || !ws['!ref']) return;
    const range = XLSX.utils.decode_range(ws['!ref']);
    const numCols = [];
    // identify "area" columns (but not %)
    for (let C = range.s.c; C <= range.e.c; C++) {
        const hCell = ws[XLSX.utils.encode_cell({ r: 0, c: C })];
        if (!hCell || hCell.v == null) continue;

        const header = hCell.v.toString().toLowerCase();

        if (header.includes('area') && !header.includes('%')) {
            numCols.push(C);
        }
    }
    // apply numeric format with comma separators
    numCols.forEach(C => {
        for (let R = 1; R <= range.e.r; R++) {
            const ref = XLSX.utils.encode_cell({ r: R, c: C });
            const cell = ws[ref];
            if (!cell || cell.v == null || cell.v === '') continue;

            const v = toNumber(cell.v);
            if (Number.isNaN(v)) continue;

            cell.t = 'n';
            cell.v = v;
            cell.z = '#,##0.00'; 
        }
    });
}

function toNumber(raw) {
    if (raw == null) return NaN;
    return parseFloat(
        raw.toString()
           .replace(/,/g, '')        // remove thousands separators
           .replace(/\s+/g, '')      // remove spaces
    );
}

</script>
<!--****CUSTOM FUNCTIONS******-->
<script src="../public/functions.js"></script>
<script src="../public/Control.MiniMaps.js"></script>
</body>
</html>
