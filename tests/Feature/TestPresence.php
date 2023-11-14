<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class TestPresence extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function testExample()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function testPresencePersonalDirectorePage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Operations Director')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalSalesManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Sales Manager')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalSalesStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Sales Staff')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalHrManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR Manager')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalHrGaPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR GA')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalHrFlPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR FL')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalSidStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','SID Staff')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalSidManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','SID Manager')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalHrAncillaryPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR Ancillary')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalBcdManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','BCD Manager')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalPmoPcPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('role_user.role_id','18')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalMsmTsPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Technical Support')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalMsmRepresentativePage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Representative')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalMsmHelpdeskPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Helpdesk')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalSolManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','SOL Manager')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalSolStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','SOL Staff')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalFinanceManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Finance & Accounting Manager')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalFinanceStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Finance Staff')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalPmoManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','PMO Manager')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalPmoStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','PMO Staff')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalPresdirPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','President Director')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalFinanceDirectorPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Finance Director')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalLeadHelpdeskPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Lead Helpdesk')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalMsmManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Manager')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalBcdStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','BCD Staff')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalBcdProcPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','BCD Procurement')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    // public function testPresencePersonalWarehousePage() {
    //     $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('role_user.role_id','41')->first())
    //         ->get('/presence');

    //     $response->assertStatus(200);
    // }

    public function testPresencePersonalPmoAdminPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','PMO Admin')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }

    public function testPresencePersonalHrStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR Staff')->first())
            ->get('/presence');

        $response->assertStatus(200);
    }








    // Presence History
    public function testPresenceHistoryDirectorePage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Operations Director')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistorySalesManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Sales Manager')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistorySalesStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Sales Staff')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryHrManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR Manager')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryHrGaPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR GA')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryHrFlPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR FL')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistorySidStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','SID Staff')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistorySidManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','SID Manager')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryHrAncillaryPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR Ancillary')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryBcdManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','BCD Manager')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryPmoPcPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('role_user.role_id','18')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryMsmTsPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Technical Support')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryMsmRepresentativePage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Representative')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryMsmHelpdeskPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Helpdesk')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistorySolManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','SOL Manager')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistorySolStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','SOL Staff')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryFinanceManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Finance & Accounting Manager')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryFinanceStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Finance Staff')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryPmoManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','PMO Manager')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryPmoStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','PMO Staff')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryPresdirPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','President Director')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryFinanceDirectorPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Financial Director')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryLeadHelpdeskPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Lead Helpdesk')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryMsmManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Manager')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryBcdStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','BCD Staff')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryBcdProcPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','BCD Procurement')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    // public function testPresencePersonalWarehousePage() {
    //     $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('role_user.role_id','41')->first())
    //         ->get('/presence');

    //     $response->assertStatus(200);
    // }

    public function testPresenceHistoryPmoAdminPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','PMO Admin')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }

    public function testPresenceHistoryHrStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR Staff')->first())
            ->get('/presence/history/personal');

        $response->assertStatus(200);
    }







    // presence report
    public function testPresenceReportHrFlPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR FL')->first())
            ->get('/presence/report');

        $response->assertStatus(200);
    }

    public function testPresenceReportHrManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR Manager')->first())
            ->get('/presence/report');

        $response->assertStatus(200);
    }

    public function testPresenceReportDirectorePage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Operations Director')->first())
            ->get('/presence/report');

        $response->assertStatus(200);
    }

    public function testPresenceReportLeadHelpdeskPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Lead Helpdesk')->first())
            ->get('/presence/report');

        $response->assertStatus(200);
    }

    public function testPresenceReportHrGaPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR GA')->first())
            ->get('/presence/report');

        $response->assertStatus(200);
    }

    public function testPresenceReportHrStaffPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR Staff')->first())
            ->get('/presence/report');

        $response->assertStatus(200);
    }

    public function testPresenceReportBcdManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','BCD Manager')->first())
            ->get('/presence/report');

        $response->assertStatus(200);
    }



    // presence shifting
    public function testPresenceShiftingHrManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR Manager')->first())
            ->get('/presence/shifting');

        $response->assertStatus(200);
    }

    public function testPresenceShiftingMsmRepresentativePage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Representative')->first())
            ->get('/presence/shifting');

        $response->assertStatus(200);
    }

    public function testPresenceShiftingMsmHoPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Head Office')->first())
            ->get('/presence/shifting');

        $response->assertStatus(200);
    }

    public function testPresenceShiftingHrGaPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','HR GA')->first())
            ->get('/presence/shifting');

        $response->assertStatus(200);
    }

    public function testPresenceShiftingLeadHelpdeskPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Lead Helpdesk')->first())
            ->get('/presence/shifting');

        $response->assertStatus(200);
    }

    public function testPresenceShiftingBcdManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','BCD Manager')->first())
            ->get('/presence/shifting');

        $response->assertStatus(200);
    }

    public function testPresenceShiftingMsmHelpdeskPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Helpdesk')->first())
            ->get('/presence/shifting');

        $response->assertStatus(200);
    }

    public function testPresenceShiftingDirectorePage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Operations Director')->first())
            ->get('/presence/shifting');

        $response->assertStatus(200);
    }


    //presence Setting
    public function testPresenceSettingLeadHelpdeskPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Lead Helpdesk')->first())
            ->get('/presence/setting');

        $response->assertStatus(200);
    }

    public function testPresenceSettingBcdManagerPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','BCD Manager')->first())
            ->get('/presence/setting');

        $response->assertStatus(200);
    }

    public function testPresenceSettingMsmHelpdeskPage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','MSM Helpdesk')->first())
            ->get('/presence/setting');

        $response->assertStatus(200);
    }

    public function testPresenceSettingDirectorePage() {
        $response = $this->actingAs(User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name','Operations Director')->first())
            ->get('/presence/setting');

        $response->assertStatus(200);
    }

}
