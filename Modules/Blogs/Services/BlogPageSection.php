<?php

namespace Modules\Blogs\Services;

use Modules\Blogs\Models\Blog as BlogModel;
use Modules\Pages\Services\SectionInterface;

class BlogPageSection implements SectionInterface
{
    public function get($section)
    {
        $blogs = BlogModel::with(['thumbnail', 'thumbnail.model']);
        if (isset($section->data->count)) {
            $blogs = $blogs->take($section->data->count);
        }
        if (isset($section->data->sort)) {
            if ($section->data->sort === 'latest') {
                $blogs = $blogs->latest('id');
            } else {
                $blogs = $blogs->orderBy('id', 'asc');
            }
        }

        $blogs = $blogs->get();

        $html = '';
        if ($section->backgroundImage) {
            $html .= '<section class="blog-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(' . $section->backgroundImage->original_url . '), url(' . url('/upload/media/placeholders/1519x870.png') . ');">';
        } else {
            if ($section->background_color === 'gray') {
                $bgColor = 'bg-gray';
                if ($section->page->dark_mode) {
                    $bgColor = 'bg-lighten';
                }
                $html .= '<section class="blog-section ' . $bgColor . '">';
            } else {
                $html .= '<section class="blog-section">';
            }
        }

        if ($section->sub_heading || $section->heading || $section->description) {
            $html .= '<div class="container heading-center position-relative z-index-1">';
        }

        if ($section->sub_heading) {
            $html .= '<p class="sub-heading">' . $section->sub_heading . '</p>';
        }
        if ($section->heading) {
            $html .= '<h2 class="section-heading">' . $section->heading . '</h2>';
        }
        if ($section->description) {
            $html .= '<p class="section-description">' . $section->description . '</p>';
        }

        if ($section->sub_heading || $section->heading || $section->description) {
            $html .= '</div>';
        }

        if ($blogs->count()) {
            $html .= '<div class="container position-relative z-index-1">';
            $html .= '<div class="row justify-content-center">';
            foreach ($blogs as $item) {
                $html .= '<div class="col-xl-4 col-lg-4 col-md-6">';
                $html .= '<div class="single-blog">';
                $html .= '<div class="blog-img">';
                $html .= '<a href="/blogs/' . $item->slug . '"><img src="' . $item->thumbnail->original_url . '" alt="' . $item->title . '"></a>';
                $html .= '<span class="date-meta">' . $item->created_at->toUserTimezone()->toUserFormat() . '</span>';
                $html .= '</div>';
                $html .= '<div class="blog-content">';
                $html .= '<h4><a href="/blogs/' . $item->slug . '">' . $item->title . '</a></h4>';
                $html .= '<p>' . $item->description . '</p>';
                $html .= '<a href="/blogs/' . $item->slug . '" class="btn btn-hover-arrow flex-shrink-0 btn-outline-primary btn-outline-2px py-lg-2 btn-block br-10">';
                $html .= '<span>READ MORE</span>';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</section>';
        return $html;
    }
}
