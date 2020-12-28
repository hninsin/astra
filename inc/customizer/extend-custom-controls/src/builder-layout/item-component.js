const {Dashicon, Button} = wp.components;


const ItemComponent = props => {

	let choices = (AstraBuilderCustomizerData && AstraBuilderCustomizerData.choices && AstraBuilderCustomizerData.choices[props.controlParams.group] ? AstraBuilderCustomizerData.choices[props.controlParams.group] : []);

	const deleteItem = (props) => {

		sessionStorage.setItem('forceRemoveComponent',  JSON.stringify(choices[props.item])  )

		let forceRemoveSection = choices[props.item];
		delete choices[props.item];

		const component_track = wp.customize('astra-settings[cloned-component-track]').get();

		let removing_index= forceRemoveSection.section.match(/\d+$/)[0];
		let existing_component_count = component_track[ forceRemoveSection.builder + '-' + forceRemoveSection.type ];

		let finalArray = component_track['removed-items'];
		finalArray.push(forceRemoveSection.section);
		finalArray = finalArray.filter(function(el, index, arr) {
			return index == arr.indexOf(el);
		});

		// If removing last item.
		if( existing_component_count == removing_index  ) {
			while (true) {
				existing_component_count = existing_component_count - 1;
				component_track[ forceRemoveSection.builder + '-' + forceRemoveSection.type ] = existing_component_count;

				var index = finalArray.indexOf( forceRemoveSection.section.replace(/[0-9]/g, existing_component_count) );
				if (index !== -1) {
					finalArray.splice(index, 1);
				} else {
					var index = finalArray.indexOf( forceRemoveSection.section.replace(/[0-9]/g, removing_index) );
					if (index !== -1) {
						finalArray.splice(index, 1);
					}
					break;
				}
			}
		}

		wp.customize('astra-settings[cloned-component-track]').set( { ...component_track, 'removed-items': finalArray }  );

	}

	return <div className="ahfb-builder-item" data-id={props.item}
				data-section={undefined !== choices[props.item] && undefined !== choices[props.item].section ? choices[props.item].section : ''}
				key={props.item} onClick={() => {
		props.focusItem(undefined !== choices[props.item] && undefined !== choices[props.item].section ? choices[props.item].section : '');
	}}>
				<span className="ahfb-builder-item-text">
					{undefined !== choices[props.item] && undefined !== choices[props.item].name ? choices[props.item].name : ''}
				</span>
		{
			choices[props.item]['clone'] &&

			<div className="ahfb-slide-up">
				<span title="Clone"
					  onClick={e => {
						  e.stopPropagation();
						  props.cloneItem(props.item);
					  }} className=" tooltip dashicons dashicons-admin-page">
				</span>
				<span title="Reset to default"
					  onClick={e => {
						  e.stopPropagation();
						  var event = new CustomEvent('AstraBuilderResetSectionControls', {
							  'detail': {
							  	'section_id': choices[props.item].section
							  }
						  });
						  document.dispatchEvent(event);
					  }}
					  className=" tooltip dashicons dashicons-image-rotate">
				</span>

				{ choices[props.item]['delete'] &&  <span title="Delete"
					  onClick={e => {
						  e.stopPropagation();
						  deleteItem(props);
						  props.removeItem(props.item);
					  }}
					  className="tooltip dashicons dashicons-trash">
				</span> }
			</div>
		}

		<Button className="ahfb-builder-item-icon" onClick={e => {
			e.stopPropagation();
			props.removeItem(props.item);
		}}>
			<Dashicon icon="no-alt"/>
		</Button>
	</div>;
};
export default ItemComponent;
