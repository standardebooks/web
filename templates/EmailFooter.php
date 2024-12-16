<?
$includeLinks = $includeLinks ?? true;
?>
		<div class="footer<? if(!$includeLinks){ ?> no-links<? } ?>">
			<? if($includeLinks){ ?>
				<p>
					<a href="<?= SITE_URL ?>/donate">Donate</a> &bull; <a href="<?= SITE_URL ?>/contribute">Get involved</a> &bull; <a href="<?= SITE_URL ?>/feeds">Ebook feeds</a>
				</p>
			<? } ?>
			<p>
				<a href="<?= SITE_URL ?>">
					<img src="https://standardebooks.org/images/logo-small.png" alt="The Standard Ebooks logo."/>
				</a>
			</p>
			<? if($includeLinks){ ?>
				<address>
					<p>Standard Ebooks L<sup>3</sup>C</p>
					<p>2027 W. Division St. Unit 106</p>
					<p>Chicago, IL 60622</p>
				</address>
			<? } ?>
		</div>
	</div>
</body>
</html>
