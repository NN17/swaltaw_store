<div class="ui clearing segment black">
	<h3><i class="cog icon spin"></i> <?=$this->lang->line('setting')?></h3>
</div>

<div class="ui grid mid-margin">
	<div class="five wide column">
		<div class="ui form">	
			<h3 class="ui dividing header pink">Shop / Company Information</h3>
			<div class="field">
				<label>Company / Shop Name</label>
				<input type="text" name="sName" required />
			</div>

			<div class="field">
				<label>Description</label>
				<input type="text" name="description" required />
			</div>

			<div class="field">
				<label>Phone Number</label>
				<input type="number" name="pNumber" required />
			</div>

			<div class="field">
				<label>Address</label>
				<textarea name="address"></textarea>
			</div>

			<div class="field text-center">
				<button type="submit" class="ui button pink">Update</button>
			</div>
		</div>
	</div>

	<div class="five wide column">
		<div class="ui form">	
			<h3 class="ui dividing header green">Printer Profile</h3>
			<div class="field">
				<label>Connection Type</label>
				<select class="ui dropdown" name="cType">
					<option value="USB">USB</option>
					<option value="NET">Network</option>
				</select>
			</div>

			<div class="field">
				<label>Printer Name</label>
				<input type="text" name="printerName" placeholder="Shared Printer Name. Example: XP-80C" required />
			</div>

			<div class="field">
				<label>IP Address</label>
				<input type="number" name="ipAddress" placeholder="Printer Address. Example: 192.168.1.1" required />
			</div>

			<div class="field text-center">
				<button type="submit" class="ui button green">Update</button>
			</div>
		</div>
	</div>
</div>