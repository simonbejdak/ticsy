<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlashMessagesTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_displays_info_message()
    {
        Session::flash('info', 'Info Message');

        $response = $this->get(route('home'));
        $response->assertSee('fixed m-10 inset-x-0 mx-auto rounded-lg flex flex-row bg-blue-400 text-white justify-center p-4 hover:opacity-100 w-2/3 z-50');
        $response->assertSee('Info Message');
    }

    public function test_it_displays_success_message()
    {
        Session::flash('success', 'Success Message');

        $response = $this->get(route('home'));
        $response->assertSee('fixed m-10 inset-x-0 mx-auto rounded-lg flex flex-row bg-green-500 text-white justify-center p-4 hover:opacity-100 w-2/3 z-50');
        $response->assertSee('Success Message');
    }

    public function test_it_displays_warning_message()
    {
        Session::flash('warning', 'Warning Message');

        $response = $this->get(route('home'));
        $response->assertSee('fixed m-10 inset-x-0 mx-auto rounded-lg flex flex-row bg-yellow-400 text-white justify-center p-4 hover:opacity-100 w-2/3 z-50');
        $response->assertSee('Warning Message');
    }

    public function test_it_displays_error_message()
    {
        Session::flash('error', 'Error Message');

        $response = $this->get(route('home'));
        $response->assertSee('fixed m-10 inset-x-0 mx-auto rounded-lg flex flex-row bg-red-500 text-white justify-center p-4 hover:opacity-100 w-2/3 z-50');
        $response->assertSee('Error Message');
    }
}
