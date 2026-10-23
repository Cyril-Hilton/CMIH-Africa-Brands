<?php
namespace Tests\Feature;

use App\Models\BrandActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandActivityExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_export_is_a_styled_workbook_and_respects_the_filter(): void
    {
        $admin = User::factory()->create(['status'=>'active', 'access_role'=>'super_admin']);
        BrandActivityLog::create(['action'=>'page_view', 'context'=>'included audit entry']);
        BrandActivityLog::create(['action'=>'verification', 'context'=>'excluded audit entry']);
        $response = $this->actingAs($admin)->get(route('brands-platform.admin.activity-export',['filter_action'=>'page_view']))
            ->assertOk()->assertHeader('Content-Type','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $file = tempnam(sys_get_temp_dir(),'cmih-export-');
        try {
            file_put_contents($file,$response->getContent());
            $zip=new \ZipArchive;
            $this->assertTrue($zip->open($file));
            $sheet=$zip->getFromName('xl/worksheets/sheet1.xml');
            $this->assertStringContainsString('included audit entry',$sheet);
            $this->assertStringNotContainsString('excluded audit entry',$sheet);
            $this->assertNotFalse($zip->getFromName('xl/styles.xml'));
            $zip->close();
        } finally { unlink($file); }
    }

    public function test_ordinary_staff_cannot_export_administration_logs(): void
    {
        $staff=User::factory()->create(['status'=>'active','access_role'=>'staff','department'=>'creatives']);
        $this->actingAs($staff)->get(route('brands-platform.admin.activity-export'))->assertForbidden();
    }
}
