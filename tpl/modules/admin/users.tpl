		<table width="100%" class="table">
			<thead>
				<tr class="titles">
					<td width="40%">Фамилия и Имя</td>
					<td width="20%" align="center">Логин</td>
					<td width="20%" align="center">Почта</td>
					<td width="15%" colspan="2">
						Действия
					</td>
				</tr>
			</thead>
			<tbody>
				<tr class="break">
					<td colspan="5"></td>
				</tr>
				{foreach $USERS as $USER}
					{include file="../../parts/user.row.tpl"}
				{/foreach}
				<tr class="break">
					<td colspan="5"></td>
				</tr>
			</tbody>
			<tfoot>
				{if $CAN_ADD}
				<tr>
					<td colspan="5" align="center">
						<a href="javascript:userAdd();" class="sky"><img src='./img/icons/add.png' style='width: 32px; height: 32px;' class="icon" />Добавить нового пользователя</a>
					</td>
				</tr>
				{/if}
				<tr>
					<td align="left" style="vertical-align: middle;">
						{include file="ajax_pagination.tpl"}
						
					</td>
					<td colspan="4" style='vertical-align: middle; text-align: right;'>
						На странице: <strong id='users-count' class='onPage'>{count($USERS)}</strong>
					</td>
				</tr>
			</tfoot>
		</table>