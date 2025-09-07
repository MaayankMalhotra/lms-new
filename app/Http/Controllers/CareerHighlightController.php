<?php

namespace App\Http\Controllers;

use App\Models\CareerHighlight;
use App\Models\CareerStat;
use App\Models\Testimonial;
use App\Models\YouTubeReview;
use Illuminate\Http\Request;

class CareerHighlightController extends Controller
{
    public function show()
    {
        $testimonials = Testimonial::orderBy('created_at', 'desc')->get();
        $highlight = CareerHighlight::with('stats')->first();
        // $youtubeReviews = YouTubeReview::orderBy('created_at', 'desc')->get();
        $youtubeReviews = YouTubeReview::all()->map(function ($review) {
            $parsedUrl = parse_url($review->video_id);
            parse_str($parsedUrl['query'] ?? '', $query);
            $review->video_id = $query['v'] ?? null;
            return $review;
        });
        //  dd($youtubeReviews);
        return view('website.reviews', compact('highlight', 'testimonials','youtubeReviews'));
    }

    public function create()
    {
        return view('admin.career-highlight.create');
    }


    public function store(Request $request)
    {

        // Step 1: Create the Career Highlight
        $careerHighlight = CareerHighlight::create([
            'heading_line' => $request->heading_line,
            'heading_highlight' => $request->heading_highlight,
            'cta_text' => $request->cta_text,
        ]);

        // Step 2: Loop through the icon/value/label arrays and create stats
        foreach ($request->icons as $index => $icon) {
            CareerStat::create([
                'career_highlight_id' => $careerHighlight->id,
                'icon' => $icon,
                'value' => $request->values[$index],
                'label' => $request->labels[$index],
            ]);
        }

        return redirect()->back()->with('success', 'Career highlight created successfully!');
    }
    public function show_career_highlight()
    {
        $highlight = CareerHighlight::with('stats')->get();
        return view('admin.career-highlight.show', compact('highlight'));
    }
    public function deleteAll()
    {
        // If using foreign key with cascade, this is sufficient:
        CareerHighlight::all()->each(function ($highlight) {
            $highlight->delete(); // deletes related stats if cascade is set
        });

        // Or, if cascade isn't set:
        // CareerStat::truncate();
        // CareerHighlight::truncate();

        return redirect()->back()->with('success', 'All career highlights and stats have been deleted.');
    }
}
