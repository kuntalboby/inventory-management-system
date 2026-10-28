<x-frontend.mainlayout :headerBannerSlider="$headerBannerSlider"
                       :pageSlug="$page->slug"
                       :basicData="$sitedata"
                       :menu="$menu"
                       >

    @section('bg_image',$page->bg_image)
    @section('title',$page->title)
    @section('bodyClass',$page->body_class)
    @section('bodyId',$page->body_id)
    {!! $content !!}
</x-frontend.mainlayout>