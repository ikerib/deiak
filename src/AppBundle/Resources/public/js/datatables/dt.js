/**
 * Created by iibarguren on 4/05/16.
 */

$(function () {
//            $("#example1").DataTable();
    $('#tinzidentziak').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "language" : {
            "sProcessing":     "Prozesatzen...",
            "sLengthMenu":     "Erakutsi _MENU_ erregistro",
            "sZeroRecords":    "Ez da emaitzarik aurkitu",
            "sEmptyTable":     "Taula hontan ez dago inongo datu erabilgarririk",
            "sInfo":           "_START_ -etik _END_ -erako erregistroak erakusten, guztira _TOTAL_ erregistro",
            "sInfoEmpty":      "0tik 0rako erregistroak erakusten, guztira 0 erregistro",
            "sInfoFiltered":   "(guztira _MAX_ erregistro iragazten)",
            "sInfoPostFix":    "",
            "sSearch":         "Aurkitu:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Abiarazten...",
            "oPaginate": {
                "sFirst":    "Lehena",
                "sLast":     "Azkena",
                "sNext":     "Hurrengoa",
                "sPrevious": "Aurrekoa"
            },
            "oAria": {
                "sSortAscending":  ": Zutabea goranzko eran ordenatzeko aktibatu ",
                "sSortDescending": ": Zutabea beheranzko eran ordenatzeko aktibatu"
            }
        }
    });

    var table = $('#tinzidentziak').DataTable();
    $('#tinzidentziak tbody').on( 'click', 'tr', function () {
        var midata=table.row( this ).data();

        var url = Routing.generate('deiak');
        console.log(url);

        $('#tdeiak').DataTable ({
            "ajax": url,
            "columns": [
                { "data": "id" },
                { "data": "created_at" },
                { "data": "userid" },
                { "data": "teknikoa" },
            ],
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "language" : {
                "sProcessing":     "Prozesatzen...",
                "sLengthMenu":     "Erakutsi _MENU_ erregistro",
                "sZeroRecords":    "Ez da emaitzarik aurkitu",
                "sEmptyTable":     "Taula hontan ez dago inongo datu erabilgarririk",
                "sInfo":           "_START_ -etik _END_ -erako erregistroak erakusten, guztira _TOTAL_ erregistro",
                "sInfoEmpty":      "0tik 0rako erregistroak erakusten, guztira 0 erregistro",
                "sInfoFiltered":   "(guztira _MAX_ erregistro iragazten)",
                "sInfoPostFix":    "",
                "sSearch":         "Aurkitu:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Abiarazten...",
                "oPaginate": {
                    "sFirst":    "Lehena",
                    "sLast":     "Azkena",
                    "sNext":     "Hurrengoa",
                    "sPrevious": "Aurrekoa"
                },
                "oAria": {
                    "sSortAscending":  ": Zutabea goranzko eran ordenatzeko aktibatu ",
                    "sSortDescending": ": Zutabea beheranzko eran ordenatzeko aktibatu"
                }
            }

        });

    } );



});
