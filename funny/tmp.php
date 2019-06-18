        <p style='font-size:12px'>Semua orang bebas untuk meng-upload image selama tidak melanggar aturan (baca disini). 
        Tidak usah pakai daftar yang ribet ini itu. Just upload and share the fun!!</p>
        <form role="form">
          <div class="form-group">
            <label for="judulPost">Judul Post</label>
            <input type="text" name='title' class="form-control input-sm" id="judulPost" placeholder="Judul yang menarik dan relevan dengan image">
          </div>
          <div class="form-group well well-sm">
            <label for="inputFile">File image yang diupload</label>
            <input type="file" name='uploadfile' id="inputFile">
            <p class="help-block">Ukuran image yg disarankan => lebar:550px tinggi:bebas!</p>
          </div>
          <div class="form-group">
            <label for="tags">Tags/Keyword (optional, pisahkan dengan koma)</label>
            <input type="text" name='tags' class="form-control input-sm" id="tags" placeholder="">
          </div>
          <div class="form-group">
            <label for="uploader">Uploader Name (optional)</label>
            <input type="text" name='uploader' class="form-control input-sm" id="uploader" placeholder="Anonim">
          </div>
          <div class="form-group">
            <label for="source">URL Sumber (optional)</label>
            <input type="text" name='source' class="form-control input-sm" id="source" placeholder="URL sumber dari image yang diupload ( http:// )">
          </div>
          <button type="submit" class="btn btn-warning">Upload</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        </form>
