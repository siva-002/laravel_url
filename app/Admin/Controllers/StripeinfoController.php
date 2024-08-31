<?php

namespace App\Admin\Controllers;

use App\Models\Stripeinfo;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StripeinfoController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Stripeinfo';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Stripeinfo());

        $grid->column('id', __('Id'));
        $grid->column('stripe_key', __('Stripe key'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Stripeinfo::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('stripe_key', __('Stripe key'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Stripeinfo());

        $form->text('stripe_key', __('Stripe key'));

        return $form;
    }
}
