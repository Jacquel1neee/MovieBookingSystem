<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about-us');
    }

    public function careers()
    {
        return view('pages.careers');
    }

    public function terms()
    {
        return view('pages.terms-of-use');
    }

    public function promotions()
    {
        return view('pages.promotions');
    }

    public function snacks()
    {
        return view('pages.gsc-snacks');
    }

    public function faq()
    {
        return view('pages.support.faq');
    }

    public function contact()
    {
        return view('pages.support.contact');
    }

    public function feedback()
    {
        return view('pages.support.feedback');
    }
}
