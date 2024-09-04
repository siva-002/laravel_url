<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\StripeSyncController;
use App\Models\StripePrices;
use App\Models\StripeProduct;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Stripe\StripeClient;

class StripePricesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'StripePrices';
    private $stripe;
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
        $grid = new Grid(new StripePrices());

        // $grid->column('id', __('Id'));
        $grid->column('productname', __('Product Name'))->display(function () {
            return $this->getProductName();
        });
        // $grid->column('product_id', __('Product id'));
        // $grid->column('price_id', __('Price id'));
        $grid->column('default_price', __('Default Price'))->display(function () {
            return $this->CheckDefaultPrice();
        });
        $grid->column('price', __('Price'))->display(function () {
            return $this->formatPrice();
        });
        $grid->column('status', __('Status'))->display(function () {
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
        $show = new Show(StripePrices::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('product_id', __('Product id'));
        $show->field('price_id', __('Price id'));
        $show->field('price', __('Price'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */

    public function updateDefaultPrice($form, $pid)
    {

        // checking default price is true then updating default price of product
        // dd($pid);
        if ($form->default_price) {

            $this->stripe->products->update(
                $form->product_id,
                [
                    'default_price' => $pid,
                    'active'=>true
                ]
            );
        } else {

            if ($form->isCreating) {
                $this->stripe->products->update(
                    $form->product_id,
                    [
                        'default_price' => null,
                        'active' => false
                    ]
                );
            } else {
                //while editing getting current default price in product if its same with current updating one then only set null
                $currentPriceId = StripeProduct::where("product_id", $form->product_id)->first()->price_id;
                if ($currentPriceId == $form->price_id) {
                    $this->stripe->products->update(
                        $form->product_id,
                        [
                            'default_price' => null,
                            'active' => false
                        ]
                    );
                }
            }

        }
    }
    protected function form()
    {
        $form = new Form(new StripePrices());
        if ($form->isEditing()) {
            $form->text('product_id', __('Product id'))->readonly();
            $form->text('price_id', __('Price id'))->readonly();
            $form->text('price', __('Price'))->readonly();
            $form->select('status', __('Status'))->options([0 => "inactive", 1 => "active"]);
            $form->select('default_price', __('Default Price'))->options([0 => "false", 1 => "true"]);
        } else {
            $allproducts = StripeProduct::all()->toArray();
            $allitems = array_map(function ($item) {
                return [$item["product_id"] => $item["plan_type"]];
            }, $allproducts);
            $all = array_merge(...$allitems);

            $form->select('product_id', __('Product id'))->options($all)->required();
            // $form->text('price_id', __('Price id'))->readonly();
            $form->text('price', __('Price'))->required();
            $form->select('status', __('Status'))->options([0 => "inactive", 1 => "active"])->readonly()->default(1);
            $form->select('default_price', __('Default Price'))->options([0 => "false", 1 => "true"])->required();
        }

        $form->saving(function (Form $form) {

            if ($form->isCreating()) {

                $newone = $this->stripe->prices->create([
                    'product' => $form->product_id,
                    'unit_amount' => $form->price * 100,
                    'currency' => "usd"
                ]);
                $newpriceid = $newone->id;
                $form->model()->price_id = $newpriceid;
                $this->updateDefaultPrice($form, $newpriceid);


                $form->model()->product_id = $newpriceid;


            } else {
                // for updating price in stripe
                $this->stripe->prices->update(
                    $form->price_id, // Price ID as a string
                    [ // Convert to cents
                        'active' => ($form->default_price || $form->status) ? true : false,
                    ]
                );
                $this->updateDefaultPrice($form, $form->price_id);
                // for updating products in stripe
                // $this->stripe->products->update(
                //     $form->product_id,
                //     [
                //         'default_price' => $form->price_id
                //     ]
                // );


            }
            unset($form->default_price);

        });

        return $form;
    }
}
