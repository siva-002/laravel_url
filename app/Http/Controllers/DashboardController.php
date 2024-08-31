<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
class DashboardController extends AdminController
{
    //
    public function index(Content $content)
    {
        return $content
            ->title('Custom Dashboard')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append(view("dashboard"));
                });
                // $row->column(6, function (Column $column) {
                //     $column->append(view("dashboard"));
                // });
            });

    }
}
