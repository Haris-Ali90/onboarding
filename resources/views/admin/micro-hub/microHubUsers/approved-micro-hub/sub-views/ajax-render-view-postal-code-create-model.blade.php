<style>
    .postal-code{
        width:66%!important;
    }
    .remScnt {
        margin: -57px 0px 0px 328px;
    }
    .remScnt1 {
        margin: -57px 0px 0px 328px;
    }
</style>
<form action="{{ route('postal-code.create') }}" method="post" name="postal_code_mi" onsubmit = "validateForm();" >
    @csrf
    @method('POST')
    <div class="form-group col-sm-12">
        <input type="hidden" id="email_address" name="email_address" value="{{$request_data['email_address']}}"/>
        <input class="form-control col-md-4" id="inputs" type="number" name="" placeholder="No of Postal Code">
        <button class=" btn btn-warning" id="add" href="#" >Add <i class="fa fa-plus"></i></button>
        <br>
        <br>
        <div  id="add_words">
            @foreach($postal_code as $postalCode)
            <div class="form-group">
                <input class=" form-control postal-code" placeholder="Postal Code" type="text" value="{{$postalCode->postal_code}}" name="postal[]" maxlength="3 " required  />
                <button class="remScnt1 btn btn-danger btn-sm">x</button>
            </div>
            @endforeach
        </div>
    </div>

    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" >
            Create Postal Code <i class="fa fa-plus"></i>
        </button>
    </div>
</form>
<script>

    function validateForm() {
        var hobbies = [];
        var checkboxes = document.getElementsByName("postal[]");

        for(var i=0; i < checkboxes.length; i++) {
            if (checkboxes[i]) {
                // Populate hobbies array with selected values
                hobbies.push(checkboxes[i].value);
            }
        }
        console.log(hobbies );
        if (hobbies.length < 1)
        {
            event.preventDefault();
            alert('Please add atleast one postal code to submit');
        }

    }

    jQuery(document).ready(function() {
        // initiate layout and plugins
        App.init();
        Admin.init();

        var scntDiv = $('#add_words');
        var wordscount = 1;

        var i = 0;
        $('#add').click(function() {
            // alert()
            var inputFields = parseInt($('#inputs').val());
            for (var n = i; n < inputFields; ++ n){
                wordscount++;
                $('<div class="form-group">' +
                    '<input class=" form-control postal-code" placeholder="Postal Code" type="text" value="" name="postal[]" maxlength="3 " required  />' +
                    '<button class="remScnt btn btn-danger btn-sm">' +
                    'x' +
                    '</button>' +
                    '</div>').appendTo(scntDiv);
                i++;
            }
            return false;
        });

        //    Remove button
        $('#add_words').on('click', '.remScnt', function() {
            console.log(i);
            if (i > 0) {
                $(this).parent().remove();
                i--;
            }
            return false;
        });

        //    Remove button
        $('#add_words').on('click', '.remScnt1', function() {
                $(this).parent().remove();

        });
    });
</script>