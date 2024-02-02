<?php
$doc_extensions = array("doc", "docx");
$pdf_ex = "pdf";
if ($record->document_type != 'sin') {
    $explode_name = explode('.', $record->document_data);
    $file_extention = end($explode_name);

    if (in_array($file_extention, $doc_extensions)) {
        $filePath = \Illuminate\Support\Facades\URL::to('/') . '/backends/thumbnail.png';
    } elseif ($file_extention == $pdf_ex) {
        $filePath = \Illuminate\Support\Facades\URL::to('/') . '/backends/pdf-thumbnail.jpg';
    } else {
        $filePath = $record->document_data;// \Illuminate\Support\Facades\URL::to('/').'/backends/admin/image-thumbnail.jpg';
    }

    if(filter_var($filePath, FILTER_VALIDATE_URL)){
        ?><a href="{{$record->document_data}}"
   target="_blank"><img src="{{$filePath}}" style="width: 80px;height: 80px;"
                        class="uploaded-file"/></a>
   <?php }
   else {
       ?><span>{{$record->document_data}}</span><?php
   }
}
else{
?>
   <span>{{$record->document_data}}</span>
<?php } ?>
