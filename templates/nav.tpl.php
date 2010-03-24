	<!-- NAVIGATION -->
		<ul id="nav"> 
		<? foreach($links as $link): ?>
			<li>
				<a class="link <? if(isset($link['ajax'])) echo $link['ajax']; ?>" href="<?=$link['href']?>"><?=$link['name']?></a>
				<ul>
				<!-- Optional Sublinks -->
				<? if(isset($link['sublinks']))
					foreach($link['sublinks'] as $sublink): ?>
					<li>
						<a class="sublink <? if(isset($sublink['ajax'])) echo $sublink['ajax']; ?>" href="<?=$sublink['href']?>"><?=$sublink['name']?></a>
					</li>
				 <? endforeach;//$links['sublinks']  ?> 
				 </ul>
				
			</li>
		<? endforeach; //$links  ?>
		</ul>
	<!-- END NAVIGATION -->
