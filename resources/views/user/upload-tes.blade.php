<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File dengan Chunk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Upload File dengan Chunk</h2>
        
        <input type="file" id="file" class="form-control mb-3">
        <button onclick="uploadFile()" class="btn btn-primary">Upload</button>

        <div id="progress-container" class="mt-3" style="display:none;">
            <div class="progress">
                <div id="progress-bar" class="progress-bar progress-bar-striped bg-success" style="width: 0%;"></div>
            </div>
            <p id="progress-text" class="mt-2">0%</p>
        </div>

        <p id="upload-status" class="mt-3"></p>
    </div>

    <script>
        function uploadFile() {
            let file = document.getElementById('file').files[0];
            if (!file) {
                alert("Pilih file terlebih dahulu!");
                return;
            }

            let chunkSize = 2 * 1024 * 1024; // 2MB per chunk
            let totalChunks = Math.ceil(file.size / chunkSize);
            let chunkIndex = 0;
            let progressContainer = $("#progress-container");
            let progressBar = $("#progress-bar");
            let progressText = $("#progress-text");

            progressContainer.show();

            function uploadChunk() {
                let start = chunkIndex * chunkSize;
                let end = Math.min(start + chunkSize, file.size);
                let chunk = file.slice(start, end);
                
                let formData = new FormData();
                formData.append("file_chunk", chunk);
                formData.append("chunk_index", chunkIndex);
                formData.append("total_chunks", totalChunks);
                formData.append("_token", "{{ csrf_token() }}");

                $.ajax({
                    url: "{{ route('upload.chunk') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        chunkIndex++;
                        let progress = Math.round((chunkIndex / totalChunks) * 100);
                        progressBar.css("width", progress + "%");
                        progressText.text(progress + "%");

                        if (chunkIndex < totalChunks) {
                            uploadChunk();
                        } else {
                            mergeChunks();
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#upload-status").html(`<span class="text-danger">Gagal mengunggah chunk</span>`);
                    }
                });
            }

            function mergeChunks() {
                $.ajax({
                    url: "{{ route('upload.merge') }}",
                    type: "POST",
                    data: {
                        total_chunks: totalChunks,
                        final_filename: file.name,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $("#upload-status").html(`<span class="text-success">${response.message}</span>`);
                    },
                    error: function(xhr, status, error) {
                        $("#upload-status").html(`<span class="text-danger">Gagal menggabungkan file</span>`);
                    }
                });
            }

            uploadChunk();
        }
    </script>
</body>
</html>
