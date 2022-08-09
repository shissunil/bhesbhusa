<div id="category-list">
        <ul class="list-unstyled">
            @if (count($superSubCategory) > 0 )
                @foreach ($superSubCategory as $category)
                    <li>
                        <h3><a href="#">{{ $category->supersub_cat_name }}</a></h3>
                        <ul>
                            @if (count($category->category_list) > 0)
                                @foreach ($category->category_list as $cat)
                                    <li>
                                        <a href="#">{{ $cat->name }}</a>
                                        <ul>
                                            @if (count($cat->subCategory_list) > 0)
                                                @foreach ($cat->subCategory_list as $subCategory)
                                                    <li>
                                                        <a href="{{ route('productList', Crypt::encrypt($subCategory->id)) }}">{{ $subCategory->name }}</a>
                                                        {{-- <ul>
                                                            <li><a href="#">Today's tasks</a></li>
                                                            <li><a href="#">Urgent</a></li>
                                                        </ul> --}}
                                                    </li>
                                                @endforeach
                                            @endif
                                            {{-- <li><a href="shop.php">Today's tasks</a></li>
                                            <li><a href="#">Urgent</a></li> --}}
                                        </ul>
                                    </li>
                                    {{-- <li>
                                        <a href="#">Overdues</a>
                                        <ul>
                                            <li><a href="shop.php">Today's tasks</a></li>
                                            <li><a href="shop.php">Urgent</a></li>
                                        </ul>
                                    </li> --}}
                                @endforeach
                            @endif
                        </ul>
                    </li>
                    {{-- <li>
                        <h3><a href="#">Tasks</a></h3>
                        <ul>
                            <li>
                                <a href="#">DrillDown (active)</a>
                                <ul>
                                    <li><a href="shop.php">Today's tasks</a></li>
                                    <li><a href="shop.php">Urgent</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Overdues</a>
                                <ul>
                                    <li><a href="shop.php">Today's tasks</a></li>
                                    <li><a href="shop.php">Urgent</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <h3><a href="#">Favourites</a></h3>
                        <ul>
                            <li><a href="shop.php">Global favs</a></li>
                            <li><a href="shop.php">My favs</a></li>
                            <li><a href="shop.php">Team favs</a></li>
                            <li><a href="shop.php">Settings</a></li>
                        </ul>
                    </li> --}}
                @endforeach
            @endif
        </ul>
</div>

<script type="text/javascript">
    $("#category-list a").click(function() {
        var link = $(this);
        var closest_ul = link.closest("ul");
        var parallel_active_links = closest_ul.find(".active")
        var closest_li = link.closest("li");
        var link_status = closest_li.hasClass("active");
        var count = 0;
        closest_ul.find("ul").slideUp(function() {
            if (++count == closest_ul.find("ul").length)
                parallel_active_links.removeClass("active");
        });
        if (!link_status) {
            closest_li.children("ul").slideDown();
            closest_li.addClass("active");
        }
    })
</script>