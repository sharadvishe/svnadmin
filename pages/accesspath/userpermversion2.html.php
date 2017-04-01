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
		<td>Access Path </td>
		<td>Users </td>
		<td> Permissions </td>
</tr>
</thead>

      <tbody>

                <?php 
		include("../../include/config.inc.php");
		$appEngine = \svnadmin\core\Engine::getInstance();
		$appEngine->checkUserAuthentication(true, ACL_MOD_USER, ACL_ACTION_VIEW);
		foreach (GetArrayValue("AccessPathList") as $ap) { 
		$o = new \svnadmin\core\entities\AccessPath;
		$accesspath = rawurldecode( $ap->getEncodedPath() );
		$o->path = $accesspath;
		$oUser = new \svnadmin\core\entities\User;
		$users = $appEngine->getAccessPathViewProvider()->getUsersOfPath($o);
		$groups = $appEngine->getAccessPathViewProvider()->getGroupsOfPath($o);
                $oGroup = new \svnadmin\core\entities\Group;
		foreach ($groups as $g) {


			$gusers = $appEngine->getGroupViewProvider()->getUsersOfGroup($g);				

			foreach ($gusers as $gu) {
				$gu->perm = $perm;
				array_push($users,$gu);
			}
		}

// Current selected group.
		$oGroup = new \svnadmin\core\entities\Group;
		$oGroup->id = $groupname;
		$oGroup->name = $groupname;
		SetValue("UserList", $users);
#		$oUser->id = $u->getEncodedName();
#		$oUser->name = $u->getEncodedName();
#		$paths = $appEngine->getAccessPathViewProvider()->getPathsOfUser( $oUser );
#		SetValue("PathList", $paths);
#		?>

                <tr>
		  <td> <a href="accesspathview.php?accesspath=<?php print($ap->getEncodedPath()); ?>"><?php print($ap->getPath()); ?></a><br> </td>
    
		  <td> 
			<table>
				<?php foreach (GetArrayValue("UserList") as $u) { 
					

				?>			
						<a href="userview.php?username=<?php print($u->getEncodedName()); ?>"><?php print($u->name); ?></a> <br/>		
				<?php } ?>
			</table>

		  </td>

                  <td>
                        <table>
                                <?php foreach (GetArrayValue("UserList") as $u) {


                                ?>
                                                <a href="userview.php?username=<?php print($u->getEncodedName()); ?>"><?php print($u->perm); ?></a> <br/>
                                <?php } ?>
                        </table>

                  </td>

		
                </tr>

                <?php } ?>

      </tbody>
      </table>
      </form>

<?php GlobalFooter(); ?>
