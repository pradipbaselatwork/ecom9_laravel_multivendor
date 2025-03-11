<?php
use App\Models\ProductsFilter;
$productFilters = ProductsFilter::productFilters();
//    dd($productFilters);
?>
<script>
    $(document).ready(function() {
        //Jquery sort Price high to low FRONT-END
        $("#sort").change(function() {
            //  this.form.submit();
            let brand = get_filter('brand');
            let color = get_filter('color');
            let size = get_filter('size');
            let price = get_filter('price');
            let sort = $("#sort").val();
            let url = $("#url").val();
            @foreach ($productFilters as $filters)
                var {{ $filters['filter_column'] }} = get_filter('{{ $filters['filter_column'] }}');
            @endforeach
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                method: 'post',
                data: {
                    @foreach ($productFilters as $filters)
                        {{ $filters['filter_column'] }}: {{ $filters['filter_column'] }},
                    @endforeach
                    url: url,
                    sort: sort,
                    size: size,
                    color: color,
                    price: price,
                    brand: brand
                },
                success: function(data) {
                    $('.filter_products').html(data);
                },
                error: function() {
                    alert("Error");
                }
            });
        });

        //SIZE FILTER
        $(".size").change(function() {
            let brand = get_filter('brand');
            let color = get_filter('color');
            let size = get_filter('size');
            let price = get_filter('price');
            let sort = $("#sort").val();
            let url = $("#url").val();
            @foreach ($productFilters as $filters)
                var {{ $filters['filter_column'] }} = get_filter('{{ $filters['filter_column'] }}');
            @endforeach
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                method: 'post',
                data: {
                    @foreach ($productFilters as $filters)
                        {{ $filters['filter_column'] }}: {{ $filters['filter_column'] }},
                    @endforeach
                    url: url,
                    sort: sort,
                    size: size,
                    color: color,
                    price: price,
                    brand: brand
                },
                success: function(data) {
                    $('.filter_products').html(data);
                },
                error: function() {
                    alert("Error");
                }
            });
        });

        //COLOR FILTER
        $(".color").change(function() {
            let brand = get_filter('brand');
            let color = get_filter('color');
            let size = get_filter('size');
            let price = get_filter('price');
            let sort = $("#sort").val();
            let url = $("#url").val();
            @foreach ($productFilters as $filters)
                var {{ $filters['filter_column'] }} = get_filter('{{ $filters['filter_column'] }}');
            @endforeach
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                method: 'post',
                data: {
                    @foreach ($productFilters as $filters)
                        {{ $filters['filter_column'] }}: {{ $filters['filter_column'] }},
                    @endforeach
                    url: url,
                    sort: sort,
                    size: size,
                    color: color,
                    price: price,
                    brand: brand
                },
                success: function(data) {
                    $('.filter_products').html(data);
                },
                error: function() {
                    alert("Error");
                }
            });
        });

        //PRICE FILTER
        $(".price").change(function() {
            let brand = get_filter('brand');
            let color = get_filter('color');
            let size = get_filter('size');
            let price = get_filter('price');
            let sort = $("#sort").val();
            let url = $("#url").val();
            @foreach ($productFilters as $filters)
                var {{ $filters['filter_column'] }} = get_filter('{{ $filters['filter_column'] }}');
            @endforeach
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                method: 'post',
                data: {
                    @foreach ($productFilters as $filters)
                        {{ $filters['filter_column'] }}: {{ $filters['filter_column'] }},
                    @endforeach
                    url: url,
                    sort: sort,
                    size: size,
                    color: color,
                    price: price,
                    brand: brand
                },
                success: function(data) {
                    $('.filter_products').html(data);
                },
                error: function() {
                    alert("Error");
                }
            });
        });

        //BRANDS FILTER
        $(".brand").change(function() {
            let brand = get_filter('brand');
            let color = get_filter('color');
            let size = get_filter('size');
            let price = get_filter('price');
            let sort = $("#sort").val();
            let url = $("#url").val();
            @foreach ($productFilters as $filters)
                var {{ $filters['filter_column'] }} = get_filter('{{ $filters['filter_column'] }}');
            @endforeach
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                method: 'post',
                data: {
                    @foreach ($productFilters as $filters)
                        {{ $filters['filter_column'] }}: {{ $filters['filter_column'] }},
                    @endforeach
                    url: url,
                    sort: sort,
                    size: size,
                    color: color,
                    price: price,
                    brand: brand
                },
                success: function(data) {
                    $('.filter_products').html(data);
                },
                error: function() {
                    alert("Error");
                }
            });
        });

        //jquery for Dynamic filter
        @foreach ($productFilters as $filter)
            //jquery fabric value sort frontend 
            $('.{{ $filter['filter_column'] }}').on('click', function() {
                var url = $("#url").val();
                let brand = get_filter('brand');
                let color = get_filter('color');
                let size = get_filter('size');
                let price = get_filter('price');
                var sort = $("#sort option:selected").val();
                @foreach ($productFilters as $filters)
                    var {{ $filters['filter_column'] }} = get_filter(
                        '{{ $filters['filter_column'] }}');
                @endforeach
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    method: 'post',
                    data: {
                        @foreach ($productFilters as $filters)
                            {{ $filters['filter_column'] }}: {{ $filters['filter_column'] }},
                        @endforeach
                        url: url,
                        sort: sort,
                        size: size,
                        color: color,
                        price: price,
                        brand: brand
                    },
                    success: function(data) {
                        $('.filter_products').html(data);
                    },
                    error: function() {
                        alert("Error");
                    }
                });
            });
        @endforeach
    });
</script>
