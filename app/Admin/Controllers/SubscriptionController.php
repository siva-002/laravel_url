<?php

namespace App\Admin\Controllers;

use App\Models\SubscriptionModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SubscriptionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SubscriptionModel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SubscriptionModel());

        $grid->column('id', __('Id'));
        $grid->column('plan_type', __('Plan type'));
        $grid->column('stripe_id', __('Stripe id'));
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
        $show = new Show(SubscriptionModel::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('plan_type', __('Plan type'));
        $show->field('stripe_id', __('Stripe id'));
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
        $form = new Form(new SubscriptionModel());

        $form->text('plan_type', __('Plan type'));
        $form->text('stripe_id', __('Stripe id'));

        return $form;
    }
}
