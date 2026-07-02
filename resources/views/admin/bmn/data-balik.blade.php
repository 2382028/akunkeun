<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Data</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafc;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        h3 {
            margin-top: 20px;
            color: #555;
        }
        .status-message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .success {
            background-color: #e6f7e6;
            color: #2c662d;
            border: 1px solid #b6e3b6;
        }
        .error {
            background-color: #fdecea;
            color: #d93025;
            border: 1px solid #f5c2c7;
        }
        ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        form {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #fdfdfd;
        }
        input[type="file"] {
            margin-top: 10px;
        }
        button {
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload Data CSV</h1>

        @if(session('status'))
            <div class="status-message success">
                {{ session('status') }}
            </div>
        @endif

        @if(session('errors'))
            <div class="status-message error">
                <p>Some rows failed to insert:</p>
                <ul>
                    @foreach(session('errors') as $error)
                        <li>Row: {{ json_encode($error['data']) }} - Error: {{ $error['error'] }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('post-dataBalik') }}" method="post" enctype="multipart/form-data">
            @csrf
            <h3>Balik Data Asset</h3>
            <input type="file" name="balikData">
            <button type="submit">Upload</button>
        </form>

        <form action="{{ route('kendaraan-dataBalik') }}" method="post" enctype="multipart/form-data">
            @csrf
            <h3>Balik Data Kendaraan</h3>
            <input type="file" name="balikDataKendaraan">
            <button type="submit">Upload</button>
        </form>
        
    </div>
</body>
</html>
