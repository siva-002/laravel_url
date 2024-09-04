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
        $grid->column('invoice_id', __('Invoice Id'));
        // $grid->column('subscription_id', __('Subscription Id'));
        $grid->column('payment_id', __('Payment id'));
        $grid->column('product_name', __('Product name'));
        $grid->column('amount', __('Amount'));
        $grid->column('payer_name', __('Payer name'));
        // $grid->column('payer_email', __('Payer email'));
        $grid->column('payment_status', __('Payment status'));
        $grid->column('payment_method', __('Payment method'));
        $grid->column('starting_date', __('Starting Date'));
        $grid->column('ending_date', __('Ending Date'));
        $grid->column('invoice_url', __('Invoice Pdf'));
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
        $show->field('invoice_id', __('Invoice Id'));
        // $show->field('subscription_id', __('Subscription Id'));
        $show->field('payment_id', __('Payment id'));
        $show->field('product_name', __('Product name'));
        $show->field('amount', __('Amount'));
        $show->field('payer_name', __('Payer name'));
        // $show->field('payer_email', __('Payer email'));
        $show->field('payment_status', __('Payment status'));
        $show->field('payment_method', __('Payment method'));
        $show->field('starting_date', __('Starting Date'));
        $show->field('ending_date', __('Ending Date'));
        $show->field('created_at', __('Created at'));
        // $show->field('updated_at', __('Updated at'));

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

        $form->text('id', __('Id'));
        $form->text('user_id', __('User id'));
        $form->text('invoice_id', __('Invoice Id'));
        // $form->text('subscription_id', __('Subscription Id'));
        $form->text('payment_id', __('Payment id'));
        $form->text('product_name', __('Product name'));
        $form->text('amount', __('Amount'));
        $form->text('payer_name', __('Payer name'));
        // $form->text('payer_email', __('Payer email'));
        $form->text('payment_status', __('Payment status'));
        $form->text('payment_method', __('Payment method'));
        $form->text('starting_date', __('Starting Date'));
        $form->text('ending_date', __('Ending Date'));
        $form->text('created_at', __('Created at'));
        // $form->text('updated_at', __('Updated at'));

        return $form;
    }
}
