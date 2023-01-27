require( 'datatables.net-dt' );
require( 'datatables.net-buttons-dt' );
require( 'datatables.net-select-dt' );

var $  = require( 'jquery' );
var dt = require( 'datatables.net' );

import './styles/datatable.css'

$( function () {
    let selectedRows = [];
    let table = $('#datatable').DataTable({
        dom: 'Blfrtip',
        select: true,
        language: {
            "emptyTable":       "Таблица пуста",
            "info":             "Позиции с _START_ по _END_ из _TOTAL_",
            "infoEmpty":        "Позиции с 0 по 0 из 0",
            "infoFiltered":     "(отфильтровано из _MAX_ позиций)",
            "lengthMenu":       "Показать _MENU_ записей",
            "search":           "Поиск:",
            "zeroRecords":      "Подходящих записей не найдено",
            "infoPostFix":      " ",
            "paginate": {
                "first":        "<<",
                "last":         ">>",
                "next":         ">",
                "previous":     "<"
            },
            select:
            {
                rows:
                {
                    _: "Выбрано записей: %d"
                }
            },
        },
        buttons: [
            {
                text: "Добавить",
                action: function()
                {
                    window.location='./add';
                }
            },
            {
                text: "Изменить",
                action: function()
                {
                    if (selectedRows.length !== 0)
                    {
                        let r = table.rows(selectedRows)
                        console.log(r);
                        for (let i = 0; i < r[0].length; i++)
                        {
                            table.row(r[0][i]).data([1, 1, 1, 1])
                        }
                        table.draw();
                    }
                    // console.log(selectedRows)
                }
            },
            {
                text: "Удалить",
                action: function()
                {
                    let arr = []
                    let r = table.rows(selectedRows)
                    let n = r.nodes().to$()
                    let children = n.children('[data-tag="id"]')
                    console.log(n)
                    console.log(children)
                    for (let i = 0; i < children.length; i++)
                    {
                        arr.push(children[i].innerHTML)
                    }
                    console.log(arr)

                    let xhr = new XMLHttpRequest();

                    xhr.open('POST', 'request', true);

                    xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4)
                    {
                        let text = JSON.parse(xhr.responseText)
                        if (text.done)
                        {
                            r.remove().draw();
                            selectedRows.splice(0, selectedRows.length)
                            console.log(selectedRows)
                            table.button(1).disable();
                            table.button(2).disable();
                        }
                    }}

                    let ghgh = JSON.stringify({
                        "add": {
                            "status": false,
                        },
                        "delete": {
                            "status": true,
                            "rows": arr
                        },
                        "update": {
                            "status": false,
                            "rows": []
                        }
                    })

                    console.log(ghgh)

                    xhr.send(ghgh);

                    
                }
            }
        ],
        pageLength: 25
    });

    table.button(1).disable();
    table.button(2).disable();

    table.on('select', function (e, dt, type, indexes)
    {
        selectedRows = selectedRows.concat(indexes);
        table.button(2).enable();

        if (selectedRows.length > 1)
        {
            table.button(1).disable();
        }
        else
        {
            table.button(1).enable();
        }
    })

    table.on('deselect', function (e, dt, type, indexes)
    {
        indexes.forEach(function (e) {
            selectedRows.splice(selectedRows.indexOf(e), 1)
        })

        if (selectedRows.length > 1)
        {
            table.button(1).disable();
        }
        else
        {
            table.button(1).enable();
        }

        if (selectedRows.length === 0)
        {
            table.button(2).disable();
        }

    })
});
