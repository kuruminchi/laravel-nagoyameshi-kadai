<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Company;
use App\Models\Admin;
use App\Models\User;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    // 会社概要ページ(indexアクション)
    // 1.未ログインのユーザーは管理者側の会社概要ページにアクセスできない
    public function test_guest_cannot_access_admin_company_index()
    {
        $response = $this->get(route('admin.company.index'));

        $response->assertRedirect(route('admin.login'));
    }

    // 2.ログイン済みの一般ユーザーは管理者側の会社概要ページにアクセスできない
    public function test_user_cannot_access_admin_company_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.company.index'));

        $response->assertRedirect(route('admin.login'));
    }

    // 3.ログイン済みの管理者は管理者側の会社概要ページにアクセスできる
    public function test_adminUser_can_access_admin_company_index()
    {
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.company.index'));

        $response->assertStatus(200);
    }


    // 会社概要編集ページ(editアクション)
    // 1.未ログインのユーザーは管理者側の会社概要編集ページにアクセスできない
    public function test_guest_cannot_access_admin_company_edit()
    {
        $company = Company::factory()->create();

        $response = $this->get(route('admin.company.edit', $company));

        $response->assertRedirect(route('admin.login'));
    }

    // 2.ログイン済みの一般ユーザーは管理者側の会社概要編集ページにアクセスできない
    public function test_user_cannot_access_admin_company_edit()
    {
        $company = Company::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.company.edit', $company));

        $response->assertRedirect(route('admin.login'));
    }

    // 3.ログイン済みの管理者は管理者側の会社概要編集ページにアクセスできる
    public function test_adminUser_can_access_admin_company_edit()
    {
        $company = Company::factory()->create();
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.company.edit', $company));

        $response->assertStatus(200);
    }


    // 会社概要更新機能(updateアクション)
    // 1.未ログインのユーザーは会社概要を更新できない
    public function test_guest_cannot_update_admin_company()
    {
        $old_details = Company::factory()->create();

        $new_details = [
            'name' => '新テスト',
            'postal_code' => '1111111',
            'address' => '新テスト',
            'representative' => '新テスト',
            'establishment_date' => '新テスト',
            'capital' => '新テスト',
            'business' => '新テスト',
            'number_of_employees' => '新テスト'
        ];

        $response = $this->patch(route('admin.company.update', $old_details), $new_details);

        $this->assertDatabaseMissing('companies', $new_details);
        $response->assertRedirect(route('admin.login'));
    }

    // 2.ログイン済みの一般ユーザーは会社概要を更新できない
    public function test_user_cannot_update_admin_company()
    {
        $user = User::factory()->create();
        $old_details = Company::factory()->create();

        $new_details = [
            'name' => '新テスト',
            'postal_code' => '1111111',
            'address' => '新テスト',
            'representative' => '新テスト',
            'establishment_date' => '新テスト',
            'capital' => '新テスト',
            'business' => '新テスト',
            'number_of_employees' => '新テスト'
        ];

        $response = $this->actingAs($user)->patch(route('admin.company.update', $old_details), $new_details);

        $this->assertDatabaseMissing('companies', $new_details);
        $response->assertRedirect(route('admin.login'));
    }

    // 3.ログイン済みの管理者は会社概要を更新できる
    public function test_adminUser_can_update_admin_company()
    {
        $adminUser = Admin::factory()->create();
        $old_details = Company::factory()->create();

        $new_details = [
            'name' => '新テスト',
            'postal_code' => '1111111',
            'address' => '新テスト',
            'representative' => '新テスト',
            'establishment_date' => '新テスト',
            'capital' => '新テスト',
            'business' => '新テスト',
            'number_of_employees' => '新テスト'
        ];

        $response = $this->actingAs($adminUser, 'admin')->patch(route('admin.company.update', $old_details), $new_details);

        $this->assertDatabaseHas('companies', $new_details);
        $response->assertRedirect(route('admin.company.index'));
    }
}
