<template>
    <div class="col-md-12">
        <editor
            :apiKey="editorKey"
            v-model="form[field]"
            :init="{
                height: 300,
                menubar: 'file edit insert format tools table tc help',
                plugins: [
                    ' lists link image charmap print preview ',
                    'searchreplace visualblocks ',
                    'insertdatetime media table paste code help wordcount',
                ],
                toolbar:
                    'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',

                image_caption: true,
                importcss_append: true,
                selector: 'textarea',
                file_picker_types: 'image',
                selector: 'textarea#file-picker',
                automatic_uploads: true,
                image_title: true,
                file_picker_callback: uploadImage,
            }"
        />

        <has-error :form="form" field="question"></has-error>
    </div>
</template>

<script>
import Editor from "@tinymce/tinymce-vue";
import { editorKey } from "./editorKey";
export default {
    components: {
        editor: Editor,
    },
    data() {
        return {
            editorKey,
        };
    },
    props: ["form", "field"],
    methods: {
        uploadImage(cb, value, meta) {
            var input = document.createElement("input");
            input.setAttribute("type", "file");
            input.setAttribute("accept", "image/*");

            /*
      Note: In modern browsers input[type="file"] is functional without
      even adding it to the DOM, but that might not be the case in some older
      or quirky browsers like IE, so you might want to add it to the DOM
      just in case, and visually hide it. And do not forget do remove it
      once you do not need it anymore.
    */

            input.onchange = function () {
                var file = this.files[0];

                var reader = new FileReader();
                reader.onload = function () {
                    /*
          Note: Now we need to register the blob in TinyMCEs image blob
          registry. In the next release this part hopefully won't be
          necessary, as we are looking to handle it internally.
        */
                    var id = "blobid" + new Date().getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(",")[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    /* call the callback and populate the Title field with the file name */
                    cb(blobInfo.blobUri(), { title: file.name });
                };
                reader.readAsDataURL(file);
            };

            input.click();
        },
    },
};
</script>
