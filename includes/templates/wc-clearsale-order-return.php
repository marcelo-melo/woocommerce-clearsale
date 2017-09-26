<input name="clearsale_save" id="clearsale_save" value="0" type="hidden" />
<?if(!empty($this->integration_code)):?>
    <?if(!empty($cs_post_meta['clearsale'])):?>
      <iframe style="position: relative; right: 12px; height: 90px;" id="iFrameStart" src="<?=$cs_post_meta['clearsale'][0]?>">
        <p>Seu Browser não suporta iframes</p>
      </iframe>
       <input onclick="jQuery('#clearsale_save').val('1');" value="Realizar nova consulta" type="submit">
    <? else: ?>
          <input onclick="jQuery('#clearsale_save').val('1');" style="background:hsl(303, 100%, 25%); color:#fff; border:0; padding:4px 10px; font-size:14px;" value="Consultar ClearSale" type="submit">
    <? endif; ?>
<?else:?>
    Configuração de integração do clearsale não encontrada. <a href="admin.php?page=wc-settings">Configure sua integração</a>.
<?endif;?>
