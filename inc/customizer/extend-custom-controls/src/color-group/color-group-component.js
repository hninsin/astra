import PropTypes from 'prop-types';
const {__} = wp.i18n;
import AstraColorPickerControl from '../common/astra-color-picker-control';
const {Tooltip} = wp.components;
import {useState} from 'react';

const ColorGroupComponent = props => {

	let htmlLabel = null;
	let htmlHelp = null;
	const {
		label,
		help,
		name
	} = props.control.params;

	const linkedSubColors = AstraBuilderCustomizerData.js_configs.sub_controls[name];
	const colorGroup = [],
		tooltips = [];

	Object.entries( linkedSubColors ).map( ( [ key,value ] ) => {
		colorGroup[value.name] = wp.customize.control( value.name ).setting.get();
		tooltips[value.name] = value.title;
	});

	const[ state , setState ] = useState(colorGroup);

	const handleChangeComplete = ( key, color='' ) => {
		let updateState = {
			...state
		};

		let value;

		if (typeof color === 'string') {
			value = color;
		} else if (undefined !== color.rgb && undefined !== color.rgb.a && 1 !== color.rgb.a) {
			value = `rgba(${color.rgb.r},${color.rgb.g},${color.rgb.b},${color.rgb.a})`;
		} else {
			value = color.hex;
		}
		
		updateState[key] = value;
		wp.customize.control( key ).setting.set(value);
		
		setState(updateState);
	};

	if (label) {
		htmlLabel = <span className="customize-control-title">{label}</span>;
	}

	if (help) {
		htmlHelp = <span className="ast-description">{help}</span>;
	}	

	let optionsHtml = Object.entries( state ).map( ( [ key,value ] ) => {
		let tooltip = tooltips[key] || __('Color', 'astra');
		let html = (
			<Tooltip key={ key } text={ tooltip }>
				<div className="color-group-item" id={ key }>
					<AstraColorPickerControl color={value ? value : ''}
					onChangeComplete={(color, backgroundType) => handleChangeComplete(key, color)}
					backgroundType={'color'}
					allowGradient={false}
					allowImage={false}/>
				</div>
			</Tooltip> 
		);

		return html;
	});

	return <>
		<div className="ast-toggle-desc-wrap">
			<label className="customizer-text">
				{htmlLabel}
				{htmlHelp}
			</label>
		</div>
		<div className="ast-field-color-group-wrap">
			{optionsHtml}
		</div>
	</>;
};

ColorGroupComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo(  ColorGroupComponent );
