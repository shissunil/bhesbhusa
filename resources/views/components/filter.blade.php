<div class="side-bar-filtter">   

    <div class="panel">       

        <div class="panel-head" data-toggle="collapse" href="#categories" role="button" aria-expanded="true"
            aria-controls="price-rang">Categories</div>

        <div class="collapse show" id="categories">

            <div class="panel-body custom-scroll-div">

                <ul>

                    @if (count($data['subCategoryList']) > 0)

                        @foreach ($data['subCategoryList'] as $subCategory)

                            <li>

                                <div class="common-check">

                                    <label class="checkbox">
                                        <div>{{ ucwords($subCategory->name) }}</div>
                                        <div class="number">
                                            {{-- (59163) --}}
                                        </div>
                                        <input type="checkbox" class="sub_category" name="subCategoryId[]" value="{{ $subCategory->id }}" onchange="filterProducts()" {{ (isset($subCategoryId) && ($subCategoryId==$subCategory->id)) ? 'checked' : ''}} >
                                        <span class="checkmark"></span>
                                    </label>

                                </div>

                            </li>

                        @endforeach

                    @endif

                </ul>

            </div>

        </div>

    </div>

    <div class="panel">

        <div class="panel-head" data-toggle="collapse" href="#price-rang" role="button" aria-expanded="true"
            aria-controls="price-rang">Price Range</div>

        <div class="collapse show" id="price-rang">

            <div class="panel-body custom-scroll-div">

                <div class="slider-box">

                    <label for="priceRange">Price Range:</label>

                    <input type="text" id="priceRange" readonly>

                    <div id="price-range" class="slider"></div>

                </div>

                {{-- <h5>12,825 products found</h5> --}}

            </div>

        </div>

    </div>

    <div class="panel">

        <div class="panel-head" data-toggle="collapse" href="#color" role="button" aria-expanded="false"
            aria-controls="color">Color</div>

        <div class="collapse show" id="color">

            <div class="panel-body custom-scroll-div">

                <ul>

                    @if (count($data['colorList']) > 0)

                        @foreach ($data['colorList'] as $color)

                            <li>

                                <div class="common-check">

                                    <label class="checkbox">
                                        <div class="color">
                                            <span style="background: {{ $color->code }};"></span>
                                            {{ ucwords($color->color) }}
                                        </div>
                                        <div class="number">
                                            {{-- (100) --}}
                                        </div>
                                        <input type="checkbox" name="colorId[]" value="{{ $color->id }}" class="color_id" onchange="filterProducts()">
                                        <span class="checkmark"></span>
                                    </label>

                                </div>

                            </li>

                        @endforeach

                    @endif

                </ul>

            </div>

        </div>

    </div>

    <div class="panel">

        <div class="panel-head" data-toggle="collapse" href="#brand" role="button" aria-expanded="false"
            aria-controls="brand">Brand</div>

        <div class="collapse show" id="brand">

            <div class="panel-body custom-scroll-div">

                {{-- <h5>Top Picks</h5> --}}

                <ul>

                    @if (count($data['brandList']) > 0)

                        @foreach ($data['brandList'] as $brand)

                            <li>

                                <div class="common-check">

                                    <label class="checkbox">
                                        <div>{{ ucwords($brand->name) }}</div>
                                        <div class="number">
                                            {{-- (100) --}}
                                        </div>
                                        <input type="checkbox" name="brandId[]" value="{{ $brand->id }}" class="brand_id" onchange="filterProducts()">
                                        <span class="checkmark"></span>
                                    </label>

                                </div>

                            </li>

                        @endforeach

                    @endif

                </ul>

            </div>

        </div>

    </div>

</div>

<script>
    /* ======== Price Of Range  ===========*/
    $(function() {
        $("#price-range").slider({
            step: 1,
            range: true,
            min: {{ $data['priceRange']['minPrice'] ?? 0 }},
            max: {{ $data['priceRange']['maxPrice'] ?? 0 }},
            values: [{{ $data['priceRange']['minPrice'] ?? 0 }},
                {{ $data['priceRange']['maxPrice'] ?? 0 }}
            ],
            slide: function(event, ui) {
                $("#priceRange").val(ui.values[0] + " - " + ui.values[1]);                
            }
        });
        $("#priceRange").val($("#price-range").slider("values", 0) + " - " + $("#price-range").slider("values",1));
        

    });
</script>
