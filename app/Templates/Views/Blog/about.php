<div class="about-container">
    <div>
        <div class="body body-preview"><?= $blogConfig['info'] ?></div>
        <hr>
        <span class="material-symbols-rounded btn edit">edit_square</span>
    </div>

    <form action="/admin/about/update" method="POST">

        <textarea name="about" id="about"><?= $blogInfoRaw ?></textarea>

        <div>
            <button class="btn">Cancelar</button>

            <input class="btn" type="submit" value="Editar">
        </div>
    </form>
</div>