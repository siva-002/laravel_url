<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\StripeSyncController;
use App\Models\StripeProduct;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Stripe\StripeClient;
class StripeProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'StripeProduct';
    // protected $product = "prod_Qm4rULmVUe532t";
    protected $stripe;
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }
    protected function grid()
    {
        $syncobj = new StripeSyncController();
        $syncobj->syncProducts();
        $syncobj->syncPrices();
        $grid = new Grid(new StripeProduct());
        // $grid->column('id', __('Id'));
        $grid->column('plan_type', __('Plan'));
        // $grid->column('product_id', __('Product id'));
        // $grid->column('price_id', __('Default Price id'));
        $grid->column('description', __('Description'));
        $grid->column('price', __('Price'))->display(function () {
            return $this->getPrice();
        });

        $grid->column('status', __('Status'))->display(function ($status) {
            return $this->formatStatus();
        });
        // $grid->column('created_at', __('Created at'));
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
        $show = new Show(StripeProduct::findOrFail($id));
        $show->field('plan_type', __('Plan type'));
        $show->field('product_id', __('Product id'));
        $show->field('price_id', __('Price id'));
        $show->field('description', __('Description'));
        $show->field('status', __('Status'))->display(function ($status) {
            return $status ? 'Active' : 'In Active';
        });
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new StripeProduct());

        if ($form->isEditing()) {
            // Fields for edit
            $form->text('product_id', __('Product id'))->readonly();
            $form->text('price_id', __(' Default Price id'))->readonly();
            $form->text('plan_type', __('Plan Type'));
            $form->select('status', __('Status'))->options([
                0 => 'Inactive',
                1 => 'Active'
            ]);
            $form->text('description', __('Description'));


        } else {
            // $form->text('product_id', __('Product id'))->readonly()->default($this->product);
            $form->text('plan_type', __('Plan type'));
            $form->text('description', __('Description'));

        }
        $form->saving(function (Form $form) {

            if ($form->isCreating()) {
                $newone = $this->stripe->products->create([
                    'description' => $form->description,
                    'name' => $form->plan_type,
                ]);
                $form->model()->price_id = $newone->default_price;
                $form->model()->product_id = $newone->id;
            } else {
                $this->stripe->products->update(
                    $form->product_id, // Price ID as a string
                    [
                        // Convert to cents
                        'name' => $form->plan_type,
                        'active' => $form->status ? true : false,
                        'default_price' => $form->price_id,
                        'description' => $form->description
                    ]
                );

            }
        });
        return $form;
    }
}
