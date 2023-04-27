<?php

namespace Database\Seeders;

use App\Models\Canton;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CantonSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$datos = [
			[1, 'CUENCA', '0101', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[2, 'GIRÓN', '0102', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[3, 'GUALACEO', '0103', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[4, 'NABÓN', '0104', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[5, 'PAUTE', '0105', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[6, 'PUCARA', '0106', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[7, 'SAN FERNANDO', '0107', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[8, 'SANTA ISABEL', '0108', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[9, 'SIGSIG', '0109', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[10, 'OÑA', '0110', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[11, 'CHORDELEG', '0111', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[12, 'EL PAN', '0112', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[13, 'SEVILLA DE ORO', '0113', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[14, 'GUACHAPALA', '0114', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[15, 'CAMILO PONCE ENRÍQUEZ', '0115', 1, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[16, 'GUARANDA', '0201', 2, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[17, 'CHILLANES', '0202', 2, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[18, 'CHIMBO', '0203', 2, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[19, 'ECHEANDÍA', '0204', 2, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[20, 'SAN MIGUEL', '0205', 2, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[21, 'CALUMA', '0206', 2, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[22, 'LAS NAVES', '0207', 2, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[23, 'AZOGUES', '0301', 3, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[24, 'BIBLIÁN', '0302', 3, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[25, 'CAÑAR', '0303', 3, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[26, 'LA TRONCAL', '0304', 3, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[27, 'EL TAMBO', '0305', 3, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[28, 'DÉLEG', '0306', 3, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[29, 'SUSCAL', '0307', 3, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[30, 'TULCÁN', '0401', 4, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[31, 'BOLÍVAR', '0402', 4, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[32, 'ESPEJO', '0403', 4, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[33, 'MIRA', '0404', 4, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[34, 'MONTÚFAR', '0405', 4, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[35, 'SAN PEDRO DE HUACA', '0406', 4, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[36, 'LATACUNGA', '0501', 5, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[37, 'LA MANÁ', '0502', 5, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[38, 'PANGUA', '0503', 5, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[39, 'PUJILI', '0504', 5, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[40, 'SALCEDO', '0505', 5, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[41, 'SAQUISILÍ', '0506', 5, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[42, 'SIGCHOS', '0507', 5, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[43, 'RIOBAMBA', '0601', 6, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[44, 'ALAUSI', '0602', 6, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[45, 'COLTA', '0603', 6, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[46, 'CHAMBO', '0604', 6, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[47, 'CHUNCHI', '0605', 6, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[48, 'GUAMOTE', '0606', 6, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[49, 'GUANO', '0607', 6, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[50, 'PALLATANGA', '0608', 6, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[51, 'PENIPE', '0609', 6, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[52, 'CUMANDÁ', '0610', 6, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[53, 'MACHALA', '0701', 7, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[54, 'ARENILLAS', '0702', 7, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[55, 'ATAHUALPA', '0703', 7, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[56, 'BALSAS', '0704', 7, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[57, 'CHILLA', '0705', 7, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[58, 'EL GUABO', '0706', 7, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[59, 'HUAQUILLAS', '0707', 7, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[60, 'MARCABELÍ', '0708', 7, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[61, 'PASAJE', '0709', 7, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[62, 'PIÑAS', '0710', 7, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[63, 'PORTOVELO', '0711', 7, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[64, 'SANTA ROSA', '0712', 7, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[65, 'ZARUMA', '0713', 7, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[66, 'LAS LAJAS', '0714', 7, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[67, 'ESMERALDAS', '0801', 8, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[68, 'ELOY ALFARO', '0802', 8, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[69, 'MUISNE', '0803', 8, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[70, 'QUININDÉ', '0804', 8, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[71, 'SAN LORENZO', '0805', 8, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[72, 'ATACAMES', '0806', 8, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[73, 'RIOVERDE', '0807', 8, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[74, 'LA CONCORDIA', '0808', 8, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[75, 'GUAYAQUIL', '0901', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[76, 'ALFREDO BAQUERIZO MORENO (JUJÁN)', '0902', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[77, 'BALAO', '0903', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[78, 'BALZAR', '0904', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[79, 'COLIMES', '0905', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[80, 'DAULE', '0906', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[81, 'DURÁN', '0907', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[82, 'EL EMPALME', '0908', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[83, 'EL TRIUNFO', '0909', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[84, 'MILAGRO', '0910', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[85, 'NARANJAL', '0911', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[86, 'NARANJITO', '0912', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[87, 'PALESTINA', '0913', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[88, 'PEDRO CARBO', '0914', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[89, 'SAMBORONDÓN', '0916', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[90, 'SANTA LUCÍA', '0918', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[91, 'SALITRE (URBINA JADO)', '0919', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[92, 'SAN JACINTO DE YAGUACHI', '0920', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[93, 'PLAYAS', '0921', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[94, 'SIMÓN BOLÍVAR', '0922', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[95, 'CORONEL MARCELINO MARIDUEÑA', '0923', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[96, 'LOMAS DE SARGENTILLO', '0924', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[97, 'NOBOL', '0925', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[98, 'GENERAL ANTONIO ELIZALDE', '0927', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[99, 'ISIDRO AYORA', '0928', 9, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[100, 'IBARRA', '1001', 10, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[101, 'ANTONIO ANTE', '1002', 10, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[102, 'COTACACHI', '1003', 10, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[103, 'OTAVALO', '1004', 10, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[104, 'PIMAMPIRO', '1005', 10, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[105, 'SAN MIGUEL DE URCUQUÍ', '1006', 10, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[106, 'LOJA', '1101', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[107, 'CALVAS', '1102', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[108, 'CATAMAYO', '1103', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[109, 'CELICA', '1104', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[110, 'CHAGUARPAMBA', '1105', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[111, 'ESPÍNDOLA', '1106', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[112, 'GONZANAMÁ', '1107', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[113, 'MACARÁ', '1108', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[114, 'PALTAS', '1109', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[115, 'PUYANGO', '1110', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[116, 'SARAGURO', '1111', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[117, 'SOZORANGA', '1112', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[118, 'ZAPOTILLO', '1113', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[119, 'PINDAL', '1114', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[120, 'QUILANGA', '1115', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[121, 'OLMEDO', '1116', 11, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[122, 'BABAHOYO', '1201', 12, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[123, 'BABA', '1202', 12, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[124, 'MONTALVO', '1203', 12, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[125, 'PUEBLOVIEJO', '1204', 12, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[126, 'QUEVEDO', '1205', 12, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[127, 'URDANETA', '1206', 12, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[128, 'VENTANAS', '1207', 12, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[129, 'VÍNCES', '1208', 12, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[130, 'PALENQUE', '1209', 12, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[131, 'BUENA FÉ', '1210', 12, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[132, 'VALENCIA', '1211', 12, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[133, 'MOCACHE', '1212', 12, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[134, 'QUINSALOMA', '1213', 12, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[135, 'PORTOVIEJO', '1301', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[136, 'BOLÍVAR', '1302', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[137, 'CHONE', '1303', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[138, 'EL CARMEN', '1304', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[139, 'FLAVIO ALFARO', '1305', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[140, 'JIPIJAPA', '1306', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[141, 'JUNÍN', '1307', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[142, 'MANTA', '1308', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[143, 'MONTECRISTI', '1309', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[144, 'PAJÁN', '1310', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[145, 'PICHINCHA', '1311', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[146, 'ROCAFUERTE', '1312', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[147, 'SANTA ANA', '1313', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[148, 'SUCRE', '1314', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[149, 'TOSAGUA', '1315', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[150, '24 DE MAYO', '1316', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[151, 'PEDERNALES', '1317', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[152, 'OLMEDO', '1318', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[153, 'PUERTO LÓPEZ', '1319', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[154, 'JAMA', '1320', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[155, 'JARAMIJÓ', '1321', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[156, 'SAN VICENTE', '1322', 13, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[157, 'MORONA', '1401', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[158, 'GUALAQUIZA', '1402', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[159, 'LIMÓN INDANZA', '1403', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[160, 'PALORA', '1404', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[161, 'SANTIAGO', '1405', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[162, 'SUCÚA', '1406', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[163, 'HUAMBOYA', '1407', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[164, 'SAN JUAN BOSCO', '1408', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[165, 'TAISHA', '1409', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[166, 'LOGROÑO', '1410', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[167, 'PABLO SEXTO', '1411', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[168, 'TIWINTZA', '1412', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[169, 'TENA', '1501', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[170, 'ARCHIDONA', '1503', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[171, 'EL CHACO', '1504', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[172, 'QUIJOS', '1507', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[173, 'CARLOS JULIO AROSEMENA TOLA', '1509', 14, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[174, 'PASTAZA', '1601', 16, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[175, 'MERA', '1602', 16, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[176, 'SANTA CLARA', '1603', 16, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[177, 'ARAJUNO', '1604', 16, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[178, 'QUITO', '1701', 17, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[179, 'CAYAMBE', '1702', 17, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[180, 'MEJIA', '1703', 17, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[181, 'PEDRO MONCAYO', '1704', 17, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[182, 'RUMIÑAHUI', '1705', 17, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[183, 'SAN MIGUEL DE LOS BANCOS', '1707', 17, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[184, 'PEDRO VICENTE MALDONADO', '1708', 17, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[185, 'PUERTO QUITO', '1709', 17, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[186, 'AMBATO', '1801', 18, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[187, 'BAÑOS DE AGUA SANTA', '1802', 18, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[188, 'CEVALLOS', '1803', 18, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[189, 'MOCHA', '1804', 18, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[190, 'PATATE', '1805', 18, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[191, 'QUERO', '1806', 18, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[192, 'SAN PEDRO DE PELILEO', '1807', 18, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[193, 'SANTIAGO DE PÍLLARO', '1808', 18, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[194, 'TISALEO', '1809', 18, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[195, 'ZAMORA', '1901', 19, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[196, 'CHINCHIPE', '1902', 19, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[197, 'NANGARITZA', '1903', 19, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[198, 'YACUAMBI', '1904', 19, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[199, 'YANTZAZA (YANZATZA)', '1905', 19, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[200, 'EL PANGUI', '1906', 19, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[201, 'CENTINELA DEL CÓNDOR', '1907', 19, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[202, 'PALANDA', '1908', 19, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[203, 'PAQUISHA', '1909', 19, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[204, 'SAN CRISTÓBAL', '2001', 20, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[205, 'ISABELA', '2002', 20, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[206, 'SANTA CRUZ', '2003', 20, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[207, 'LAGO AGRIO', '2101', 21, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[208, 'GONZALO PIZARRO', '2102', 21, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[209, 'PUTUMAYO', '2103', 21, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[210, 'SHUSHUFINDI', '2104', 21, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[211, 'SUCUMBÍOS', '2105', 21, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[212, 'CASCALES', '2106', 21, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[213, 'CUYABENO', '2107', 21, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[214, 'ORELLANA', '2201', 22, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[215, 'AGUARICO', '2202', 22, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[216, 'LA JOYA DE LOS SACHAS', '2203', 22, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[217, 'LORETO', '2204', 22, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[218, 'SANTO DOMINGO', '2301', 24, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[219, 'SANTA ELENA', '2401', 24, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[220, 'LA LIBERTAD', '2402', 24, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[221, 'SALINAS', '2403', 24, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[222, 'LAS GOLONDRINAS', '9001', 25, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[223, 'MANGA DEL CURA', '9003', 25, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[224, 'EL PIEDRERO', '9004', 25, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[225, 'ARCHIDONA', '9005', 15, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[226, 'CARLOS JULIO AROSEMENA', '9006', 15, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[227, 'EL CHACO', '9007', 15, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[228, 'QUIJOS', '9008', 15, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[229, 'TENA', '9009', 15, '2023-02-24 19:24:16', '2023-02-24 19:24:16'],
			[230, 'MOMPICHE', '0809', 8, NULL, NULL],
			[231, 'MACAS', '1510', 14, NULL, NULL],
		];
		foreach ($datos as $fila) {
            DB::insert('INSERT INTO `cantones` (`id`, `canton`, `cod_canton`, `provincia_id`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?)', $fila);
        }

		/*
        Canton::create( [
    		'id'=>1,
    		'canton'=>'CUENCA',
    		'cod_canton'=>'0101',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>2,
    		'canton'=>'GIRÓN',
    		'cod_canton'=>'0102',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>3,
    		'canton'=>'GUALACEO',
    		'cod_canton'=>'0103',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>4,
    		'canton'=>'NABÓN',
    		'cod_canton'=>'0104',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>5,
    		'canton'=>'PAUTE',
    		'cod_canton'=>'0105',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>6,
    		'canton'=>'PUCARA',
    		'cod_canton'=>'0106',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>7,
    		'canton'=>'SAN FERNANDO',
    		'cod_canton'=>'0107',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>8,
    		'canton'=>'SANTA ISABEL',
    		'cod_canton'=>'0108',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>9,
    		'canton'=>'SIGSIG',
    		'cod_canton'=>'0109',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>10,
    		'canton'=>'OÑA',
    		'cod_canton'=>'0110',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>11,
    		'canton'=>'CHORDELEG',
    		'cod_canton'=>'0111',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>12,
    		'canton'=>'EL PAN',
    		'cod_canton'=>'0112',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>13,
    		'canton'=>'SEVILLA DE ORO',
    		'cod_canton'=>'0113',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>14,
    		'canton'=>'GUACHAPALA',
    		'cod_canton'=>'0114',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>15,
    		'canton'=>'CAMILO PONCE ENRÍQUEZ',
    		'cod_canton'=>'0115',
    		'provincia_id'=>1,

    	] );



    	Canton::create( [
    		'id'=>16,
    		'canton'=>'GUARANDA',
    		'cod_canton'=>'0201',
    		'provincia_id'=>2,

    	] );



    	Canton::create( [
    		'id'=>17,
    		'canton'=>'CHILLANES',
    		'cod_canton'=>'0202',
    		'provincia_id'=>2,

    	] );



    	Canton::create( [
    		'id'=>18,
    		'canton'=>'CHIMBO',
    		'cod_canton'=>'0203',
    		'provincia_id'=>2,

    	] );



    	Canton::create( [
    		'id'=>19,
    		'canton'=>'ECHEANDÍA',
    		'cod_canton'=>'0204',
    		'provincia_id'=>2,

    	] );



    	Canton::create( [
    		'id'=>20,
    		'canton'=>'SAN MIGUEL',
    		'cod_canton'=>'0205',
    		'provincia_id'=>2,

    	] );



    	Canton::create( [
    		'id'=>21,
    		'canton'=>'CALUMA',
    		'cod_canton'=>'0206',
    		'provincia_id'=>2,

    	] );



    	Canton::create( [
    		'id'=>22,
    		'canton'=>'LAS NAVES',
    		'cod_canton'=>'0207',
    		'provincia_id'=>2,

    	] );



    	Canton::create( [
    		'id'=>23,
    		'canton'=>'AZOGUES',
    		'cod_canton'=>'0301',
    		'provincia_id'=>3,

    	] );



    	Canton::create( [
    		'id'=>24,
    		'canton'=>'BIBLIÁN',
    		'cod_canton'=>'0302',
    		'provincia_id'=>3,

    	] );



    	Canton::create( [
    		'id'=>25,
    		'canton'=>'CAÑAR',
    		'cod_canton'=>'0303',
    		'provincia_id'=>3,

    	] );



    	Canton::create( [
    		'id'=>26,
    		'canton'=>'LA TRONCAL',
    		'cod_canton'=>'0304',
    		'provincia_id'=>3,

    	] );



    	Canton::create( [
    		'id'=>27,
    		'canton'=>'EL TAMBO',
    		'cod_canton'=>'0305',
    		'provincia_id'=>3,

    	] );



    	Canton::create( [
    		'id'=>28,
    		'canton'=>'DÉLEG',
    		'cod_canton'=>'0306',
    		'provincia_id'=>3,

    	] );



    	Canton::create( [
    		'id'=>29,
    		'canton'=>'SUSCAL',
    		'cod_canton'=>'0307',
    		'provincia_id'=>3,

    	] );



    	Canton::create( [
    		'id'=>30,
    		'canton'=>'TULCÁN',
    		'cod_canton'=>'0401',
    		'provincia_id'=>4,

    	] );



    	Canton::create( [
    		'id'=>31,
    		'canton'=>'BOLÍVAR',
    		'cod_canton'=>'0402',
    		'provincia_id'=>4,

    	] );



    	Canton::create( [
    		'id'=>32,
    		'canton'=>'ESPEJO',
    		'cod_canton'=>'0403',
    		'provincia_id'=>4,

    	] );



    	Canton::create( [
    		'id'=>33,
    		'canton'=>'MIRA',
    		'cod_canton'=>'0404',
    		'provincia_id'=>4,

    	] );



    	Canton::create( [
    		'id'=>34,
    		'canton'=>'MONTÚFAR',
    		'cod_canton'=>'0405',
    		'provincia_id'=>4,

    	] );



    	Canton::create( [
    		'id'=>35,
    		'canton'=>'SAN PEDRO DE HUACA',
    		'cod_canton'=>'0406',
    		'provincia_id'=>4,

    	] );



    	Canton::create( [
    		'id'=>36,
    		'canton'=>'LATACUNGA',
    		'cod_canton'=>'0501',
    		'provincia_id'=>5,

    	] );



    	Canton::create( [
    		'id'=>37,
    		'canton'=>'LA MANÁ',
    		'cod_canton'=>'0502',
    		'provincia_id'=>5,

    	] );



    	Canton::create( [
    		'id'=>38,
    		'canton'=>'PANGUA',
    		'cod_canton'=>'0503',
    		'provincia_id'=>5,

    	] );



    	Canton::create( [
    		'id'=>39,
    		'canton'=>'PUJILI',
    		'cod_canton'=>'0504',
    		'provincia_id'=>5,

    	] );



    	Canton::create( [
    		'id'=>40,
    		'canton'=>'SALCEDO',
    		'cod_canton'=>'0505',
    		'provincia_id'=>5,

    	] );



    	Canton::create( [
    		'id'=>41,
    		'canton'=>'SAQUISILÍ',
    		'cod_canton'=>'0506',
    		'provincia_id'=>5,

    	] );



    	Canton::create( [
    		'id'=>42,
    		'canton'=>'SIGCHOS',
    		'cod_canton'=>'0507',
    		'provincia_id'=>5,

    	] );



    	Canton::create( [
    		'id'=>43,
    		'canton'=>'RIOBAMBA',
    		'cod_canton'=>'0601',
    		'provincia_id'=>6,

    	] );



    	Canton::create( [
    		'id'=>44,
    		'canton'=>'ALAUSI',
    		'cod_canton'=>'0602',
    		'provincia_id'=>6,

    	] );



    	Canton::create( [
    		'id'=>45,
    		'canton'=>'COLTA',
    		'cod_canton'=>'0603',
    		'provincia_id'=>6,

    	] );



    	Canton::create( [
    		'id'=>46,
    		'canton'=>'CHAMBO',
    		'cod_canton'=>'0604',
    		'provincia_id'=>6,

    	] );



    	Canton::create( [
    		'id'=>47,
    		'canton'=>'CHUNCHI',
    		'cod_canton'=>'0605',
    		'provincia_id'=>6,

    	] );



    	Canton::create( [
    		'id'=>48,
    		'canton'=>'GUAMOTE',
    		'cod_canton'=>'0606',
    		'provincia_id'=>6,

    	] );



    	Canton::create( [
    		'id'=>49,
    		'canton'=>'GUANO',
    		'cod_canton'=>'0607',
    		'provincia_id'=>6,

    	] );



    	Canton::create( [
    		'id'=>50,
    		'canton'=>'PALLATANGA',
    		'cod_canton'=>'0608',
    		'provincia_id'=>6,

    	] );



    	Canton::create( [
    		'id'=>51,
    		'canton'=>'PENIPE',
    		'cod_canton'=>'0609',
    		'provincia_id'=>6,

    	] );



    	Canton::create( [
    		'id'=>52,
    		'canton'=>'CUMANDÁ',
    		'cod_canton'=>'0610',
    		'provincia_id'=>6,

    	] );



    	Canton::create( [
    		'id'=>53,
    		'canton'=>'MACHALA',
    		'cod_canton'=>'0701',
    		'provincia_id'=>7,

    	] );



    	Canton::create( [
    		'id'=>54,
    		'canton'=>'ARENILLAS',
    		'cod_canton'=>'0702',
    		'provincia_id'=>7,

    	] );



    	Canton::create( [
    		'id'=>55,
    		'canton'=>'ATAHUALPA',
    		'cod_canton'=>'0703',
    		'provincia_id'=>7,

    	] );



    	Canton::create( [
    		'id'=>56,
    		'canton'=>'BALSAS',
    		'cod_canton'=>'0704',
    		'provincia_id'=>7,

    	] );



    	Canton::create( [
    		'id'=>57,
    		'canton'=>'CHILLA',
    		'cod_canton'=>'0705',
    		'provincia_id'=>7,

    	] );



    	Canton::create( [
    		'id'=>58,
    		'canton'=>'EL GUABO',
    		'cod_canton'=>'0706',
    		'provincia_id'=>7,

    	] );



    	Canton::create( [
    		'id'=>59,
    		'canton'=>'HUAQUILLAS',
    		'cod_canton'=>'0707',
    		'provincia_id'=>7,

    	] );



    	Canton::create( [
    		'id'=>60,
    		'canton'=>'MARCABELÍ',
    		'cod_canton'=>'0708',
    		'provincia_id'=>7,

    	] );



    	Canton::create( [
    		'id'=>61,
    		'canton'=>'PASAJE',
    		'cod_canton'=>'0709',
    		'provincia_id'=>7,

    	] );



    	Canton::create( [
    		'id'=>62,
    		'canton'=>'PIÑAS',
    		'cod_canton'=>'0710',
    		'provincia_id'=>7,

    	] );



    	Canton::create( [
    		'id'=>63,
    		'canton'=>'PORTOVELO',
    		'cod_canton'=>'0711',
    		'provincia_id'=>7,

    	] );



    	Canton::create( [
    		'id'=>64,
    		'canton'=>'SANTA ROSA',
    		'cod_canton'=>'0712',
    		'provincia_id'=>7,

    	] );



    	Canton::create( [
    		'id'=>65,
    		'canton'=>'ZARUMA',
    		'cod_canton'=>'0713',
    		'provincia_id'=>7,

    	] );



    	Canton::create( [
    		'id'=>66,
    		'canton'=>'LAS LAJAS',
    		'cod_canton'=>'0714',
    		'provincia_id'=>7,

    	] );



    	Canton::create( [
    		'id'=>67,
    		'canton'=>'ESMERALDAS',
    		'cod_canton'=>'0801',
    		'provincia_id'=>8,

    	] );



    	Canton::create( [
    		'id'=>68,
    		'canton'=>'ELOY ALFARO',
    		'cod_canton'=>'0802',
    		'provincia_id'=>8,

    	] );



    	Canton::create( [
    		'id'=>69,
    		'canton'=>'MUISNE',
    		'cod_canton'=>'0803',
    		'provincia_id'=>8,

    	] );



    	Canton::create( [
    		'id'=>70,
    		'canton'=>'QUININDÉ',
    		'cod_canton'=>'0804',
    		'provincia_id'=>8,

    	] );



    	Canton::create( [
    		'id'=>71,
    		'canton'=>'SAN LORENZO',
    		'cod_canton'=>'0805',
    		'provincia_id'=>8,

    	] );



    	Canton::create( [
    		'id'=>72,
    		'canton'=>'ATACAMES',
    		'cod_canton'=>'0806',
    		'provincia_id'=>8,

    	] );



    	Canton::create( [
    		'id'=>73,
    		'canton'=>'RIOVERDE',
    		'cod_canton'=>'0807',
    		'provincia_id'=>8,

    	] );



    	Canton::create( [
    		'id'=>74,
    		'canton'=>'LA CONCORDIA',
    		'cod_canton'=>'0808',
    		'provincia_id'=>8,

    	] );



    	Canton::create( [
    		'id'=>75,
    		'canton'=>'GUAYAQUIL',
    		'cod_canton'=>'0901',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>76,
    		'canton'=>'ALFREDO BAQUERIZO MORENO (JUJÁN)',
    		'cod_canton'=>'0902',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>77,
    		'canton'=>'BALAO',
    		'cod_canton'=>'0903',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>78,
    		'canton'=>'BALZAR',
    		'cod_canton'=>'0904',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>79,
    		'canton'=>'COLIMES',
    		'cod_canton'=>'0905',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>80,
    		'canton'=>'DAULE',
    		'cod_canton'=>'0906',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>81,
    		'canton'=>'DURÁN',
    		'cod_canton'=>'0907',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>82,
    		'canton'=>'EL EMPALME',
    		'cod_canton'=>'0908',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>83,
    		'canton'=>'EL TRIUNFO',
    		'cod_canton'=>'0909',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>84,
    		'canton'=>'MILAGRO',
    		'cod_canton'=>'0910',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>85,
    		'canton'=>'NARANJAL',
    		'cod_canton'=>'0911',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>86,
    		'canton'=>'NARANJITO',
    		'cod_canton'=>'0912',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>87,
    		'canton'=>'PALESTINA',
    		'cod_canton'=>'0913',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>88,
    		'canton'=>'PEDRO CARBO',
    		'cod_canton'=>'0914',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>89,
    		'canton'=>'SAMBORONDÓN',
    		'cod_canton'=>'0916',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>90,
    		'canton'=>'SANTA LUCÍA',
    		'cod_canton'=>'0918',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>91,
    		'canton'=>'SALITRE (URBINA JADO)',
    		'cod_canton'=>'0919',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>92,
    		'canton'=>'SAN JACINTO DE YAGUACHI',
    		'cod_canton'=>'0920',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>93,
    		'canton'=>'PLAYAS',
    		'cod_canton'=>'0921',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>94,
    		'canton'=>'SIMÓN BOLÍVAR',
    		'cod_canton'=>'0922',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>95,
    		'canton'=>'CORONEL MARCELINO MARIDUEÑA',
    		'cod_canton'=>'0923',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>96,
    		'canton'=>'LOMAS DE SARGENTILLO',
    		'cod_canton'=>'0924',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>97,
    		'canton'=>'NOBOL',
    		'cod_canton'=>'0925',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>98,
    		'canton'=>'GENERAL ANTONIO ELIZALDE',
    		'cod_canton'=>'0927',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>99,
    		'canton'=>'ISIDRO AYORA',
    		'cod_canton'=>'0928',
    		'provincia_id'=>9,

    	] );



    	Canton::create( [
    		'id'=>100,
    		'canton'=>'IBARRA',
    		'cod_canton'=>'1001',
    		'provincia_id'=>10,

    	] );



    	Canton::create( [
    		'id'=>101,
    		'canton'=>'ANTONIO ANTE',
    		'cod_canton'=>'1002',
    		'provincia_id'=>10,

    	] );



    	Canton::create( [
    		'id'=>102,
    		'canton'=>'COTACACHI',
    		'cod_canton'=>'1003',
    		'provincia_id'=>10,

    	] );



    	Canton::create( [
    		'id'=>103,
    		'canton'=>'OTAVALO',
    		'cod_canton'=>'1004',
    		'provincia_id'=>10,

    	] );



    	Canton::create( [
    		'id'=>104,
    		'canton'=>'PIMAMPIRO',
    		'cod_canton'=>'1005',
    		'provincia_id'=>10,

    	] );



    	Canton::create( [
    		'id'=>105,
    		'canton'=>'SAN MIGUEL DE URCUQUÍ',
    		'cod_canton'=>'1006',
    		'provincia_id'=>10,

    	] );



    	Canton::create( [
    		'id'=>106,
    		'canton'=>'LOJA',
    		'cod_canton'=>'1101',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>107,
    		'canton'=>'CALVAS',
    		'cod_canton'=>'1102',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>108,
    		'canton'=>'CATAMAYO',
    		'cod_canton'=>'1103',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>109,
    		'canton'=>'CELICA',
    		'cod_canton'=>'1104',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>110,
    		'canton'=>'CHAGUARPAMBA',
    		'cod_canton'=>'1105',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>111,
    		'canton'=>'ESPÍNDOLA',
    		'cod_canton'=>'1106',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>112,
    		'canton'=>'GONZANAMÁ',
    		'cod_canton'=>'1107',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>113,
    		'canton'=>'MACARÁ',
    		'cod_canton'=>'1108',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>114,
    		'canton'=>'PALTAS',
    		'cod_canton'=>'1109',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>115,
    		'canton'=>'PUYANGO',
    		'cod_canton'=>'1110',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>116,
    		'canton'=>'SARAGURO',
    		'cod_canton'=>'1111',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>117,
    		'canton'=>'SOZORANGA',
    		'cod_canton'=>'1112',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>118,
    		'canton'=>'ZAPOTILLO',
    		'cod_canton'=>'1113',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>119,
    		'canton'=>'PINDAL',
    		'cod_canton'=>'1114',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>120,
    		'canton'=>'QUILANGA',
    		'cod_canton'=>'1115',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>121,
    		'canton'=>'OLMEDO',
    		'cod_canton'=>'1116',
    		'provincia_id'=>11,

    	] );



    	Canton::create( [
    		'id'=>122,
    		'canton'=>'BABAHOYO',
    		'cod_canton'=>'1201',
    		'provincia_id'=>12,

    	] );



    	Canton::create( [
    		'id'=>123,
    		'canton'=>'BABA',
    		'cod_canton'=>'1202',
    		'provincia_id'=>12,

    	] );



    	Canton::create( [
    		'id'=>124,
    		'canton'=>'MONTALVO',
    		'cod_canton'=>'1203',
    		'provincia_id'=>12,

    	] );



    	Canton::create( [
    		'id'=>125,
    		'canton'=>'PUEBLOVIEJO',
    		'cod_canton'=>'1204',
    		'provincia_id'=>12,

    	] );



    	Canton::create( [
    		'id'=>126,
    		'canton'=>'QUEVEDO',
    		'cod_canton'=>'1205',
    		'provincia_id'=>12,

    	] );



    	Canton::create( [
    		'id'=>127,
    		'canton'=>'URDANETA',
    		'cod_canton'=>'1206',
    		'provincia_id'=>12,

    	] );



    	Canton::create( [
    		'id'=>128,
    		'canton'=>'VENTANAS',
    		'cod_canton'=>'1207',
    		'provincia_id'=>12,

    	] );



    	Canton::create( [
    		'id'=>129,
    		'canton'=>'VÍNCES',
    		'cod_canton'=>'1208',
    		'provincia_id'=>12,

    	] );



    	Canton::create( [
    		'id'=>130,
    		'canton'=>'PALENQUE',
    		'cod_canton'=>'1209',
    		'provincia_id'=>12,

    	] );



    	Canton::create( [
    		'id'=>131,
    		'canton'=>'BUENA FÉ',
    		'cod_canton'=>'1210',
    		'provincia_id'=>12,

    	] );



    	Canton::create( [
    		'id'=>132,
    		'canton'=>'VALENCIA',
    		'cod_canton'=>'1211',
    		'provincia_id'=>12,

    	] );



    	Canton::create( [
    		'id'=>133,
    		'canton'=>'MOCACHE',
    		'cod_canton'=>'1212',
    		'provincia_id'=>12,

    	] );



    	Canton::create( [
    		'id'=>134,
    		'canton'=>'QUINSALOMA',
    		'cod_canton'=>'1213',
    		'provincia_id'=>12,

    	] );



    	Canton::create( [
    		'id'=>135,
    		'canton'=>'PORTOVIEJO',
    		'cod_canton'=>'1301',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>136,
    		'canton'=>'BOLÍVAR',
    		'cod_canton'=>'1302',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>137,
    		'canton'=>'CHONE',
    		'cod_canton'=>'1303',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>138,
    		'canton'=>'EL CARMEN',
    		'cod_canton'=>'1304',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>139,
    		'canton'=>'FLAVIO ALFARO',
    		'cod_canton'=>'1305',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>140,
    		'canton'=>'JIPIJAPA',
    		'cod_canton'=>'1306',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>141,
    		'canton'=>'JUNÍN',
    		'cod_canton'=>'1307',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>142,
    		'canton'=>'MANTA',
    		'cod_canton'=>'1308',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>143,
    		'canton'=>'MONTECRISTI',
    		'cod_canton'=>'1309',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>144,
    		'canton'=>'PAJÁN',
    		'cod_canton'=>'1310',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>145,
    		'canton'=>'PICHINCHA',
    		'cod_canton'=>'1311',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>146,
    		'canton'=>'ROCAFUERTE',
    		'cod_canton'=>'1312',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>147,
    		'canton'=>'SANTA ANA',
    		'cod_canton'=>'1313',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>148,
    		'canton'=>'SUCRE',
    		'cod_canton'=>'1314',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>149,
    		'canton'=>'TOSAGUA',
    		'cod_canton'=>'1315',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>150,
    		'canton'=>'24 DE MAYO',
    		'cod_canton'=>'1316',
    		'provincia_id'=>13,

    	] );

        Canton::create( [
    		'id'=>151,
    		'canton'=>'PEDERNALES',
    		'cod_canton'=>'1317',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>152,
    		'canton'=>'OLMEDO',
    		'cod_canton'=>'1318',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>153,
    		'canton'=>'PUERTO LÓPEZ',
    		'cod_canton'=>'1319',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>154,
    		'canton'=>'JAMA',
    		'cod_canton'=>'1320',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>155,
    		'canton'=>'JARAMIJÓ',
    		'cod_canton'=>'1321',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>156,
    		'canton'=>'SAN VICENTE',
    		'cod_canton'=>'1322',
    		'provincia_id'=>13,

    	] );



    	Canton::create( [
    		'id'=>157,
    		'canton'=>'MORONA',
    		'cod_canton'=>'1401',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>158,
    		'canton'=>'GUALAQUIZA',
    		'cod_canton'=>'1402',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>159,
    		'canton'=>'LIMÓN INDANZA',
    		'cod_canton'=>'1403',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>160,
    		'canton'=>'PALORA',
    		'cod_canton'=>'1404',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>161,
    		'canton'=>'SANTIAGO',
    		'cod_canton'=>'1405',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>162,
    		'canton'=>'SUCÚA',
    		'cod_canton'=>'1406',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>163,
    		'canton'=>'HUAMBOYA',
    		'cod_canton'=>'1407',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>164,
    		'canton'=>'SAN JUAN BOSCO',
    		'cod_canton'=>'1408',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>165,
    		'canton'=>'TAISHA',
    		'cod_canton'=>'1409',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>166,
    		'canton'=>'LOGROÑO',
    		'cod_canton'=>'1410',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>167,
    		'canton'=>'PABLO SEXTO',
    		'cod_canton'=>'1411',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>168,
    		'canton'=>'TIWINTZA',
    		'cod_canton'=>'1412',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>169,
    		'canton'=>'TENA',
    		'cod_canton'=>'1501',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>170,
    		'canton'=>'ARCHIDONA',
    		'cod_canton'=>'1503',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>171,
    		'canton'=>'EL CHACO',
    		'cod_canton'=>'1504',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>172,
    		'canton'=>'QUIJOS',
    		'cod_canton'=>'1507',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>173,
    		'canton'=>'CARLOS JULIO AROSEMENA TOLA',
    		'cod_canton'=>'1509',
    		'provincia_id'=>14,

    	] );



    	Canton::create( [
    		'id'=>174,
    		'canton'=>'PASTAZA',
    		'cod_canton'=>'1601',
    		'provincia_id'=>16,

    	] );



    	Canton::create( [
    		'id'=>175,
    		'canton'=>'MERA',
    		'cod_canton'=>'1602',
    		'provincia_id'=>16,

    	] );



    	Canton::create( [
    		'id'=>176,
    		'canton'=>'SANTA CLARA',
    		'cod_canton'=>'1603',
    		'provincia_id'=>16,

    	] );



    	Canton::create( [
    		'id'=>177,
    		'canton'=>'ARAJUNO',
    		'cod_canton'=>'1604',
    		'provincia_id'=>16,

    	] );



    	Canton::create( [
    		'id'=>178,
    		'canton'=>'QUITO',
    		'cod_canton'=>'1701',
    		'provincia_id'=>17,

    	] );



    	Canton::create( [
    		'id'=>179,
    		'canton'=>'CAYAMBE',
    		'cod_canton'=>'1702',
    		'provincia_id'=>17,

    	] );



    	Canton::create( [
    		'id'=>180,
    		'canton'=>'MEJIA',
    		'cod_canton'=>'1703',
    		'provincia_id'=>17,

    	] );



    	Canton::create( [
    		'id'=>181,
    		'canton'=>'PEDRO MONCAYO',
    		'cod_canton'=>'1704',
    		'provincia_id'=>17,

    	] );



    	Canton::create( [
    		'id'=>182,
    		'canton'=>'RUMIÑAHUI',
    		'cod_canton'=>'1705',
    		'provincia_id'=>17,

    	] );



    	Canton::create( [
    		'id'=>183,
    		'canton'=>'SAN MIGUEL DE LOS BANCOS',
    		'cod_canton'=>'1707',
    		'provincia_id'=>17,

    	] );



    	Canton::create( [
    		'id'=>184,
    		'canton'=>'PEDRO VICENTE MALDONADO',
    		'cod_canton'=>'1708',
    		'provincia_id'=>17,

    	] );



    	Canton::create( [
    		'id'=>185,
    		'canton'=>'PUERTO QUITO',
    		'cod_canton'=>'1709',
    		'provincia_id'=>17,

    	] );



    	Canton::create( [
    		'id'=>186,
    		'canton'=>'AMBATO',
    		'cod_canton'=>'1801',
    		'provincia_id'=>18,

    	] );



    	Canton::create( [
    		'id'=>187,
    		'canton'=>'BAÑOS DE AGUA SANTA',
    		'cod_canton'=>'1802',
    		'provincia_id'=>18,

    	] );



    	Canton::create( [
    		'id'=>188,
    		'canton'=>'CEVALLOS',
    		'cod_canton'=>'1803',
    		'provincia_id'=>18,

    	] );



    	Canton::create( [
    		'id'=>189,
    		'canton'=>'MOCHA',
    		'cod_canton'=>'1804',
    		'provincia_id'=>18,

    	] );



    	Canton::create( [
    		'id'=>190,
    		'canton'=>'PATATE',
    		'cod_canton'=>'1805',
    		'provincia_id'=>18,

    	] );



    	Canton::create( [
    		'id'=>191,
    		'canton'=>'QUERO',
    		'cod_canton'=>'1806',
    		'provincia_id'=>18,

    	] );



    	Canton::create( [
    		'id'=>192,
    		'canton'=>'SAN PEDRO DE PELILEO',
    		'cod_canton'=>'1807',
    		'provincia_id'=>18,

    	] );



    	Canton::create( [
    		'id'=>193,
    		'canton'=>'SANTIAGO DE PÍLLARO',
    		'cod_canton'=>'1808',
    		'provincia_id'=>18,

    	] );



    	Canton::create( [
    		'id'=>194,
    		'canton'=>'TISALEO',
    		'cod_canton'=>'1809',
    		'provincia_id'=>18,

    	] );



    	Canton::create( [
    		'id'=>195,
    		'canton'=>'ZAMORA',
    		'cod_canton'=>'1901',
    		'provincia_id'=>19,

    	] );



    	Canton::create( [
    		'id'=>196,
    		'canton'=>'CHINCHIPE',
    		'cod_canton'=>'1902',
    		'provincia_id'=>19,

    	] );





    	Canton::create( [
    		'id'=>197,
    		'canton'=>'NANGARITZA',
    		'cod_canton'=>'1903',
    		'provincia_id'=>19,

    	] );


    	Canton::create( [
    		'id'=>198,
    		'canton'=>'YACUAMBI',
    		'cod_canton'=>'1904',
    		'provincia_id'=>19,

    	] );



    	Canton::create( [
    		'id'=>199,
    		'canton'=>'YANTZAZA (YANZATZA)',
    		'cod_canton'=>'1905',
    		'provincia_id'=>19,

    	] );



    	Canton::create( [
    		'id'=>200,
    		'canton'=>'EL PANGUI',
    		'cod_canton'=>'1906',
    		'provincia_id'=>19,

    	] );



    	Canton::create( [
    		'id'=>201,
    		'canton'=>'CENTINELA DEL CÓNDOR',
    		'cod_canton'=>'1907',
    		'provincia_id'=>19,

    	] );



    	Canton::create( [
    		'id'=>202,
    		'canton'=>'PALANDA',
    		'cod_canton'=>'1908',
    		'provincia_id'=>19,

    	] );



    	Canton::create( [
    		'id'=>203,
    		'canton'=>'PAQUISHA',
    		'cod_canton'=>'1909',
    		'provincia_id'=>19,

    	] );



    	Canton::create( [
    		'id'=>204,
    		'canton'=>'SAN CRISTÓBAL',
    		'cod_canton'=>'2001',
    		'provincia_id'=>20,

    	] );



    	Canton::create( [
    		'id'=>205,
    		'canton'=>'ISABELA',
    		'cod_canton'=>'2002',
    		'provincia_id'=>20,

    	] );



    	Canton::create( [
    		'id'=>206,
    		'canton'=>'SANTA CRUZ',
    		'cod_canton'=>'2003',
    		'provincia_id'=>20,

    	] );



    	Canton::create( [
    		'id'=>207,
    		'canton'=>'LAGO AGRIO',
    		'cod_canton'=>'2101',
    		'provincia_id'=>21,

    	] );



    	Canton::create( [
    		'id'=>208,
    		'canton'=>'GONZALO PIZARRO',
    		'cod_canton'=>'2102',
    		'provincia_id'=>21,

    	] );



    	Canton::create( [
    		'id'=>209,
    		'canton'=>'PUTUMAYO',
    		'cod_canton'=>'2103',
    		'provincia_id'=>21,

    	] );



    	Canton::create( [
    		'id'=>210,
    		'canton'=>'SHUSHUFINDI',
    		'cod_canton'=>'2104',
    		'provincia_id'=>21,

    	] );



    	Canton::create( [
    		'id'=>211,
    		'canton'=>'SUCUMBÍOS',
    		'cod_canton'=>'2105',
    		'provincia_id'=>21,

    	] );



    	Canton::create( [
    		'id'=>212,
    		'canton'=>'CASCALES',
    		'cod_canton'=>'2106',
    		'provincia_id'=>21,

    	] );



    	Canton::create( [
    		'id'=>213,
    		'canton'=>'CUYABENO',
    		'cod_canton'=>'2107',
    		'provincia_id'=>21,

    	] );



    	Canton::create( [
    		'id'=>214,
    		'canton'=>'ORELLANA',
    		'cod_canton'=>'2201',
    		'provincia_id'=>22,

    	] );



    	Canton::create( [
    		'id'=>215,
    		'canton'=>'AGUARICO',
    		'cod_canton'=>'2202',
    		'provincia_id'=>22,

    	] );



    	Canton::create( [
    		'id'=>216,
    		'canton'=>'LA JOYA DE LOS SACHAS',
    		'cod_canton'=>'2203',
    		'provincia_id'=>22,

    	] );



    	Canton::create( [
    		'id'=>217,
    		'canton'=>'LORETO',
    		'cod_canton'=>'2204',
    		'provincia_id'=>22,

    	] );



    	Canton::create( [
    		'id'=>218,
    		'canton'=>'SANTO DOMINGO',
    		'cod_canton'=>'2301',
    		'provincia_id'=>24,

    	] );



    	Canton::create( [
    		'id'=>219,
    		'canton'=>'SANTA ELENA',
    		'cod_canton'=>'2401',
    		'provincia_id'=>24,

    	] );



    	Canton::create( [
    		'id'=>220,
    		'canton'=>'LA LIBERTAD',
    		'cod_canton'=>'2402',
    		'provincia_id'=>24,

    	] );



    	Canton::create( [
    		'id'=>221,
    		'canton'=>'SALINAS',
    		'cod_canton'=>'2403',
    		'provincia_id'=>24,

    	] );



    	Canton::create( [
    		'id'=>222,
    		'canton'=>'LAS GOLONDRINAS',
    		'cod_canton'=>'9001',
    		'provincia_id'=>25,

    	] );



    	Canton::create( [
    		'id'=>223,
    		'canton'=>'MANGA DEL CURA',
    		'cod_canton'=>'9003',
    		'provincia_id'=>25,

    	] );



    	Canton::create( [
    		'id'=>224,
    		'canton'=>'EL PIEDRERO',
    		'cod_canton'=>'9004',
    		'provincia_id'=>25,

    	] );
    	Canton::create( [
    		'id'=>225,
    		'canton'=>'ARCHIDONA',
    		'cod_canton'=>'9005',
    		'provincia_id'=>15,

    	] );
    	Canton::create( [
    		'id'=>226,
    		'canton'=>'CARLOS JULIO AROSEMENA',
    		'cod_canton'=>'9006',
    		'provincia_id'=>15,

    	] );
    	Canton::create( [
    		'id'=>227,
    		'canton'=>'EL CHACO',
    		'cod_canton'=>'9007',
    		'provincia_id'=>15,

    	] );
    	Canton::create( [
    		'id'=>228,
    		'canton'=>'QUIJOS',
    		'cod_canton'=>'9008',
    		'provincia_id'=>15,

    	] );
    	Canton::create( [
    		'id'=>229,
    		'canton'=>'TENA',
    		'cod_canton'=>'9009',
    		'provincia_id'=>15,

    	] );
		*/
	}
}
