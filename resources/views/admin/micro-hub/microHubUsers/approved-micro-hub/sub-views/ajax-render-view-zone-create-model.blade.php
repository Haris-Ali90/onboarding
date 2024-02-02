<style>
    .postal-code{
        width:66%!important;
    }
    .remScnt {
        margin: -56px 0px 0px 513px ;
    }
</style>
<form action="{{ route('zone.create') }}" method="post">
    @csrf
    @method('POST')
    <div class="form-group col-sm-12">
        <label>Zone Type</label>
        <select class="form-control " name="zone_type" id="zone_type" required>
            <option value="">Please Select Zone Type</option>
            <?php
            foreach($zoneType as $zones){
                echo '<option value="'.$zones->id.'">'.$zones->title.'</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group col-sm-12">
        <input type="text" name="title" value="{{isset($zones_routing->title) ? $zones_routing->title : '' }}" id="title" pattern="[A-Za-z0-9]{1}[A-Za-z 0-9()]{0,40}" placeholder="Title" class="form-control"  required/>
    </div>
    <div class="form-group col-sm-12">
        <input type="hidden" id="email_address" name="email_address" value="{{$request_data['email_address']}}"/>
        <input class="form-control col-md-4 inputs" id="inputs" type="number" name="" placeholder="No of Postal Code" required>
        <button class=" btn btn-warning" id="add-postal-codes" href="#" >Add <i class="fa fa-plus"></i></button>
        <br>
        <br>
        <div  id="add_postal_code"></div>
    </div>

    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" >
            Create Zone <i class="fa fa-plus"></i>
        </button>
    </div>
</form>
<script>
    jQuery(document).ready(function() {
        // initiate layout and plugins
        App.init();
        Admin.init();

        var scntDiv = $('#add_postal_code');
        var wordscount = 1;

        var i = 0;
        $('#add-postal-codes').click(function() {
            // alert()
            var inputFields = parseInt($('.inputs').val());
            console.log(inputFields);
            for (var n = i; n < inputFields; ++ n){
                wordscount++;
                $('<div class="form-group"><input class=" form-control postal-code" placeholder="Postal Code" type="text" value="" name="postal[]" maxlength="3 " required  /><button class="remScnt btn btn-danger btn-sm">x</button></div>').appendTo(scntDiv);
                i++;
            }
            return false;
        });

        //    Remove button
        $('#add_postal_code').on('click', '.remScnt', function() {
            if (i > 0) {
                $(this).parent().remove();
                i--;
            }
            return false;
        });


        make_option_selected('#zone_type','{{ old('zone_type',$zone_type) }}');
    });
</script>