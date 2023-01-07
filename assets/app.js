/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
require( 'datatables.net-dt' );
require( 'datatables.net-buttons-dt' );
require( 'datatables.net-select-dt' );

var $  = require( 'jquery' );
var dt = require( 'datatables.net' );

import './styles/app.scss';
import './styles/datatable.css'

// start the Stimulus application
import './bootstrap';

// import { registerVueControllerComponents } from '@symfony/ux-vue';
// registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));

$( function () {
        $('#datatable').DataTable({
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
                },
                {
                    text: "Изменить",
                },
                {
                    text: "Удалить",
                }
            ]
        });
} );