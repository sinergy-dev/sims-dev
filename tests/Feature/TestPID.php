<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\RoleUser;
use App\User;

class TestPID extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function testPid()
    // {
    //     $listUserPid = RoleUser::join('roles', 'role_user.role_id', '=', 'roles.id')
    //                         ->join('roles_feature', 'roles_feature.role_id', '=', 'roles.id')
    //                         ->join('features', 'features.id', '=', 'roles_feature.feature_id')
    //                         ->select('roles.name')
    //                         ->where('features.name','ID Project')->get();

    //     foreach ($listUserPid as $data) {
    //         $response = $this->actingAs('roles.name',$data)
    //         ->get('/salesproject')->assertStatus(200);
    //     }
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function testPidSalesManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Sales Manager')->first())
            ->get('/salesproject');

        $response->assertStatus(200);

        // var_dump(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Finance & Accounting Manager')->first()->nik);
    }

    public function testPidSalesStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Sales Staff')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidDirectorePage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Operations Director')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidMsmManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Manager')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidBcdStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','BCD Staff')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidBcdProcPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','BCD Procurement')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidHrManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR Manager')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidHrGaPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR GA')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidHrFlPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR FL')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidSidStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','SID Staff')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidSidManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','SID Manager')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidBcdManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','BCD Manager')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidPmoPcPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('role_user.role_id','18')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidMsmRepresentativePage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Representative')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidMsmTsPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Technical Support')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidFinanceManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Finance & Accounting Manager')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidFinanceStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Finance Staff')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidPmoManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','PMO Manager')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidPmoStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','PMO Staff')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }
    
    public function testPidMsmHoPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Head Office')->first())
            ->get('/salesproject/shifting');

        $response->assertStatus(200);
    }

    public function testPidSolManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','SOL Manager')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidSolStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','SOL Staff')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidsmHelpdeskPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Helpdesk')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidLeadHelpdeskPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Lead Helpdesk')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidPresdirPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','President Director')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

    public function testPidFinanceDirectorPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Financial Director')->first())
            ->get('/salesproject');

        $response->assertStatus(200);
    }

}
