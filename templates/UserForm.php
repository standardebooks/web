<?
$user = $user ?? new User();
$isEditForm = $isEditForm ?? false;
$generateNewUuid = $generateNewUuid ?? false;
$passwordAction = $passwordAction ?? Enums\PasswordActionType::None;
?>

<label class="email">
	Email
	<input
		type="email"
		name="user-email"
		required="required"
		value="<?= Formatter::EscapeHtml($user->Email) ?>"
	/>
</label>

<label class="icon user">
	Name
	<input
		type="text"
		name="user-name"
		value="<?= Formatter::EscapeHtml($user->Name) ?>"
	/>
</label>
<fieldset>
	<label>
		UUID
		<input
			type="text"
			name="user-uuid"
			value="<?= Formatter::EscapeHtml($user->Uuid) ?>"
		/>
	</label>
	<label>
		<input type="hidden" name="generate-new-uuid" value="false" />
		<input type="checkbox" name="generate-new-uuid" value="true"<? if($generateNewUuid){ ?> checked="checked"<? } ?> />
		Generate a new UUID
	</label>
</fieldset>
<fieldset>
	<ul>
		<? if($user->PasswordHash === null){ ?>
			<li>
				<fieldset>
					<label>
						<input type="checkbox" name="password-action" value="<?= Enums\PasswordActionType::Edit->value ?>"<? if($passwordAction == Enums\PasswordActionType::Edit){ ?> checked="checked"<? } ?> />Set a password
					</label>
					<label>
						Password
						<input
							type="password"
							name="user-password"
						/>
					</label>
				</fieldset>
			</li>
		<? }else{ ?>
			<li>
				<label>
					<input type="radio" name="password-action" value="<?= Enums\PasswordActionType::None->value ?>"<? if($passwordAction == Enums\PasswordActionType::None){ ?> checked="checked"<? } ?> />Don’t change password
				</label>
			</li>
			<li>
				<label>
					<input type="radio" name="password-action" value="<?= Enums\PasswordActionType::Delete->value ?>"<? if($passwordAction == Enums\PasswordActionType::Delete){ ?> checked="checked"<? } ?> />Remove password
				</label>
			</li>
			<li>
				<fieldset>
					<label>
						<input type="radio" name="password-action" value="<?= Enums\PasswordActionType::Edit->value ?>"<? if($passwordAction == Enums\PasswordActionType::Edit){ ?> checked="checked"<? } ?> />Change password
					</label>
					<label>
						Password
						<input
							type="password"
							name="user-password"
						/>
					</label>
				</fieldset>
			</li>
		<? } ?>
	</ul>
</fieldset>
<fieldset>
	<legend>Benefits</legend>
	<ul>
		<li>
			<label>
				<input type="hidden" name="benefits-can-access-feeds" value="false" />
				<input type="checkbox" name="benefits-can-access-feeds" value="true"<? if($user->Benefits->CanAccessFeeds){ ?> checked="checked"<? } ?> />
				Can access feeds
			</label>
		</li>
		<li>
			<label>
				<input type="hidden" name="benefits-can-vote" value="false" />
				<input type="checkbox" name="benefits-can-vote" value="true"<? if($user->Benefits->CanVote){ ?> checked="checked"<? } ?> />
				Can vote in polls
			</label>
		</li>
		<li>
			<label>
				<input type="hidden" name="benefits-can-bulk-download" value="false" />
				<input type="checkbox" name="benefits-can-bulk-download" value="true"<? if($user->Benefits->CanBulkDownload){ ?> checked="checked"<? } ?> />
				Can access bulk downloads
			</label>
		</li>
		<li>
			<label>
				<input type="hidden" name="benefits-can-upload-artwork" value="false" />
				<input type="checkbox" name="benefits-can-upload-artwork" value="true"<? if($user->Benefits->CanUploadArtwork){ ?> checked="checked"<? } ?> />
				Can upload artwork
			</label>
		</li>
		<li>
			<label>
				<input type="hidden" name="benefits-can-review-artwork" value="false" />
				<input type="checkbox" name="benefits-can-review-artwork" value="true"<? if($user->Benefits->CanReviewArtwork){ ?> checked="checked"<? } ?> />
				Can review artwork
			</label>
		</li>
		<li>
			<label>
				<input type="hidden" name="benefits-can-review-own-artwork" value="false" />
				<input type="checkbox" name="benefits-can-review-own-artwork" value="true"<? if($user->Benefits->CanReviewOwnArtwork){ ?> checked="checked"<? } ?> />
				Can review own artwork
			</label>
		</li>
		<li>
			<label>
				<input type="hidden" name="benefits-can-edit-users" value="false" />
				<input type="checkbox" name="benefits-can-edit-users" value="true"<? if($user->Benefits->CanEditUsers){ ?> checked="checked"<? } ?> />
				Can edit users
			</label>
		</li>
		<li>
			<label>
				<input type="hidden" name="benefits-can-create-ebook-placeholder" value="false" />
				<input type="checkbox" name="benefits-can-create-ebook-placeholder" value="true"<? if($user->Benefits->CanCreateEbookPlaceholders){ ?> checked="checked"<? } ?> />
				Can create ebook placeholders
			</label>
		</li>
	</ul>
</fieldset>

<div class="footer">
	<button>
		<? if($isEditForm){ ?>
			Save changes
		<? }else{ ?>
			Submit
		<? } ?>
	</button>
</div>
