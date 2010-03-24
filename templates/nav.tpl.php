	<!-- NAVIGATION -->
		<div id="nav"> 
		<? foreach($links as $link): ?>
			<div style="float:left;">
				<a class="link <? if(isset($link['ajax'])) echo $link['ajax']; ?>" href="<?=$link['href']?>"><?=$link['name']?></a>
				<div>
				<!-- Optional Sublinks -->
				<? if(isset($link['sublinks']))
					foreach($link['sublinks'] as $sublink): ?>
					<span>
						<a class="sublink <? if(isset($sublink['ajax'])) echo $sublink['ajax']; ?>" href="<?=$sublink['href']?>"><?=$sublink['name']?></a>
					</span>
				 <? endforeach;//$links['sublinks']  ?> 
				 </div>
				
			</div>
		<? endforeach; //$links  ?>
		</div>
	<!-- END NAVIGATION -->
