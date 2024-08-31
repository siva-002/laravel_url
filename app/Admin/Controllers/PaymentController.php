<?php

namespace App\Admin\Controllers;

use App\Models\Payment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PaymentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Payment';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Payment());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('payment_id', __('Payment id'));
        $grid->column('product_name', __('Product name'));
        $grid->column('quantity', __('Quantity'));
        $grid->column('amount', __('Amount'));
        $grid->column('payer_name', __('Payer name'));
        $grid->column('payer_email', __('Payer email'));
        $grid->column('payment_status', __('Payment status'));
        $grid->column('payment_method', __('Payment method'));
        $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Payment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('payment_id', __('Payment id'));
        $show->field('product_name', __('Product name'));
        $show->field('quantity', __('Quantity'));
        $show->field('amount', __('Amount'));
        $show->field('payer_name', __('Payer name'));
        $show->field('payer_email', __('Payer email'));
        $show->field('payment_status', __('Payment status'));
        $show->field('payment_method', __('Payment method'));
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
        $form = new Form(new Payment());

        $form->text('user_id', __('User id'));
        $form->text('payment_id', __('Payment id'));
        $form->text('product_name', __('Product name'));
        $form->text('quantity', __('Quantity'));
        $form->text('amount', __('Amount'));
        $form->text('payer_name', __('Payer name'));
        $form->text('payer_email', __('Payer email'));
        $form->text('payment_status', __('Payment status'));
        $form->text('payment_method', __('Payment method'));

        return $form;
    }
}
