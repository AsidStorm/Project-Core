		<div class="menu margin-left-zero">
        	<ul class="items" id="M_ITEMS">
				{foreach $A_MODULES as $MODULE}
					<li class="item {if $MODULE.id eq {$CURR_MODULE}}active{assign var="haveActive" value="1"}{/if}"><a href="./{$MODULE_NAME}.php?act=modules&id={$MODULE.id}" class="item-a" id="module_{$MODULE.id}">{$MODULE.title}</a></li>
				{/foreach}
				<li class="item"><a href="javascript:moduleAdd();" class="item-a"><img src='./img/icons/add.png' style='width: 16px; height: 16px; vertical-align: middle; padding-right: 3px;' />Добавить</a></li>
			</ul>
            <br class="clr" />
		</div>
		<div style='padding: 5px;'></div>
		{if $CURR_MODULE neq 0}
		<table width="100%" border="0" class="table">
			<thead>
				<tr class="titles">
					<td width="40%">Название действия</td>
					<td width="20%" align="center">Видимость</td>
					<td width="20%" align="center">Определение</td>
					<td width="15%" colspan="2">
						<span>Действия</span>
					</td>
				</tr>
			</thead>
			<tbody>
				<tr class="break preData">
					<td colspan="5"></td>
				</tr>
				{foreach $ACTIONS as $ACTION}
					{include file="../../parts/module.row.tpl"}
				{/foreach}
				<tr class="break afterData">
					<td colspan="5"></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2" align="center">
						<a href='javascript:moduleDelete({$CURR_MODULE}, "{$CURR_TITLE}");' class='sky'><img src='./img/icons/delete.png' style='width: 32px; height: 32px;' class='icon' />Удалить модуль</a>
						<a href="javascript:moduleEdit({$CURR_MODULE}, '{$CURR_TITLE}');" class='sky'><img src='./img/icons/edit.png' style='width: 32px; height: 32px;' class='icon' />Редактировать модуль</a>
					</td>
					<td colspan="3" align="center">
						<a href="javascript:actionAdd({$CURR_MODULE}, '{$CURR_TITLE}');" class='sky'><img src='./img/icons/add.png' style='width: 32px; height: 32px;' class='icon' />Добавить новое действие</a>
					</td>
				</tr>
				<tr>
					<td align="left" style="vertical-align: middle;">
						{include file="ajax_pagination.tpl"}
					</td>
					<td colspan="4" style='vertical-align: middle; text-align: right;'>
						На странице: <strong id='actions-count' class='onPage'>{count($ACTIONS)}</strong>
					</td>
				</tr>
			</tfoot>
		</table>
		{/if}