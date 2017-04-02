<?php GlobalHeader(); ?>

<script type="text/javascript">
$(document).ready(function(){
  $("#selectall").click(function(){
    selectAll(this, "selected_accesspaths[]");
  });
});
</script>

<h1><?php Translate("Access-Path management"); ?></h1>
<p class="hdesc"><?php Translate("Here you can see a list of all access path, which are defined in your subversion configuration."); ?></p>

<?php HtmlFilterBox("accesspathlist", 1); ?>

<form action="accesspathslist.php" method="POST">
<table id="accesspathlist" class="datatable">
<thead>
<tr>
		<td> Users </td>
		<td> Access Paths </td>
		<td> Permissions </td>
</tr>
</thead>

      <tbody>

                <?php 
		include("../../include/config.inc.php");
		$appEngine = \svnadmin\core\Engine::getInstance();
		$appEngine->checkUserAuthentication(true, ACL_MOD_USER, ACL_ACTION_VIEW);
		foreach (GetArrayValue("UserList") as $u) { 
		$oUser = new \svnadmin\core\entities\User;
		$oUser->id = $u->getEncodedName();
		$oUser->name = $u->getEncodedName();
		$paths = $appEngine->getAccessPathViewProvider()->getPathsOfUser( $oUser );
		$len_access_paths = sizeof($paths);
		SetValue("PathList", $paths);
		if ($len_access_paths > 0){
		?>

                <tr>
                  <td><a href="userview.php?username=<?php print($u->getEncodedName()); ?>"><?php print($u->getDisplayName()); ?></a></td>

                  <td>
                        <table>
                                 <?php foreach (GetArrayValue("PathList") as $ap) { ?>                              
					  <a href="accesspathview.php?accesspath=<?php print($ap->getEncodedPath()); ?>"><?php print($ap->getPath()); ?></a><br>
                                 <?php } ?>

                        </table>
                  </td>

                  <td>
                        <table>
                                 <?php foreach (GetArrayValue("PathList") as $ap) { ?>
                                       <?php print($ap->perm); ?>  </br>
                                 <?php } ?>

                        </table>
                  </td>
                </tr>

                <?php }} ?>

      </tbody>
      </table>
      </form>

<?php GlobalFooter(); ?>
