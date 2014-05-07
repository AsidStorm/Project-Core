								<div class='loader'>
									<img src='./img/icons/loader.gif' />
								</div>
							</div> <!-- .content -->
						</div> <!-- .body -->
					</div> <!-- .page -->
				</div> <!-- .scroll-fix -->
			</div> <!-- div -->
		</div> <!-- .scroll-fix-wrapper -->
		<div style='padding: 3px;'></div>
		<script>
			MODULE_NAME   = "{$MODULE_NAME}";
			MODULE_ACTION = "{$MODULE_ACTION}";
			
			PARAMS['fid'] = parseInt("{$smarty.get.fid}") || -1; // FILE_ID
			PARAMS['id']  = parseInt("{$smarty.get.id}")  || -1;
		</script>
		<script type="text/javascript">
			{$ALERT}
		</script>
	</body>
</html>