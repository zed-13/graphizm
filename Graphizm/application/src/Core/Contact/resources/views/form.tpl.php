<div class="modal fade" id="form-contact" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form-contactLabel"><?php echo t("Nouveau message"); ?> : </h4>
      </div>
      <div class="modal-body">
        <div class="info">
            <?php
                if (isset($intro)):
                    echo $intro;
                endif;
            ?>
        </div>
        <form>
          <div class="form-group">
            <label for="recipient-name" class="control-label"><?php echo t("Votre adresse e-mail");?> : </label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label"><?php echo t("Message"); ?> : </label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo t("Fermer"); ?></button>
        <button type="button" class="btn btn-primary"><?php echo t("Envoyer le message"); ?></button>
      </div>
    </div>
  </div>
</div>
