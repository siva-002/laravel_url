<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\Userid;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        // $user = new Userid();
        // $grid = new Grid($user);
        // $grid->column('id', __('Id'));
        // $grid->column('name', __('Name'))->display(function () {
        //     return $this->getName();
        // });
        // $grid->column('user_id', __('User id'));
        // $grid->column('email', __('Email'))->display(function () {
        //     return $this->getEmail();
        // });
        // $grid->column('generated_urls', __('Generated Urls'))->display(function () {
        //     return $this->generatedUrlCount();
        // });
        // // $grid->column('password', __('Password'));
        // $grid->column('created_at', __('Created at'))->display(function () {
        //     return $this->getCreatedDate();
        // });
        // // $grid->column('updated_at', __('Updated at'));
        // $grid->column('user_status', __('User Status'))->display(function () {
        //     return $this->getStatus();
        //     // return $this->user->user_id;
        // });
        $user = new User();
        $grid = new Grid($user);
        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('user_id', __('User id'));
        $grid->column('email', __('Email'));
        $grid->column('generated_urls', __('Generated Urls'))->display(function () {
            return $this->generatedUrlCount();
        });
        // $grid->column('password', __('Password'));
        $grid->column('created_at', __('Created at'))->display(function () {
            return $this->getCreatedDate();
        });
        // $grid->column('updated_at', __('Updated at'));
        $grid->column('user_status', __('User Status'))->display(function () {
            return $this->getStatus();
            // return $this->user->user_id;
        });

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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('user_id', __('User id'));
        $show->field('email', __('Email'));
        $show->field('password', __('Password'));
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
        $form = new Form(new User());

        $form->text('name', __('Name'));
        $form->text('user_id', __('User id'));
        $form->text('email', __('Email'));
        $form->text('password', __('Password'));
        $form->text('user_status', __('User Status'));

        return $form;
    }
}
