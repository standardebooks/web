<?
$includeLinks ??= true;
?>
		<div class="footer<? if(!$includeLinks){ ?> no-links<? } ?>">
			<? if($includeLinks){ ?>
				<p>
					<a href="<?= SITE_URL ?>/donate">Donate</a> • <a href="<?= SITE_URL ?>/contribute">Get involved</a> • <a href="<?= SITE_URL ?>/blog">Blog</a> • <a href="<?= SITE_URL ?>/feeds">Ebook feeds</a>
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
					<p>1658 N. Milwaukee Ave. Unit 343</p>
					<p>Chicago, IL 60647</p>
				</address>
			<? } ?>
		</div>
	</div>
</body>
</html>
