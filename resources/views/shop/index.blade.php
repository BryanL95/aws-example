<html>
    <body>
        <h1>Hello World</h1>
        <form action="{{ url('/images') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group">
                <input type="file" name="image" id="image">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
        <a href="{{url('my-image')}}">Descargar</a>
    </body>
</html>