<!DOCTYPE html>
<html lang="en">

<head>
	<title>Service Generator</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<script src="jquery.js"></script>
	<link href="bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
</head>

<body>
	<div class="container mx-auto">
		<div class="row mt-5 mb-5">
			<button class="btn btn-primary add-entity">Add Entity Name</button>

		</div>
		<label>Entity base package</label>
		<input type="text" class="form-control" id="base-package" placeholder="com.yourcompany.project.entity" />
		<br>
		<div id="entities">
			<div class="row" style="flex-wrap: nowrap;">
				<div class="col col-md-11" style="padding-right: 0!important;"><input type="text"
						class="form-control entity" placeholder="Entity name" /></div>
				<div class="col col-md-1" style="padding-left: 0!important;"> <a class="remove-entity"
						style="margin-top: 8px;color: red;"><i class="fa fa-minus-circle"
							style="margin-top: 8px;"></i></a></div>


			</div>
			</br>
		</div>
		<div class="row mt-5">
			<button class="btn btn-success generate">Generate Services</button>
		</div>
	</div>

</body>
<script>
	$(document).ready(function () {

		$(".generate").click(function () {

			$(".entity").each(function (index, element) {
				let basePackageName = $("#base-package").val()
				generateInter($(element).val(), basePackageName)
				generateImpl($(element).val(), basePackageName)
				generateDAO($(element).val(),basePackageName)
			})
		})
		function download(filename, text) {
			var element = document.createElement('a');
			element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
			element.setAttribute('download', filename);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);
		}
		function copyEntity() {
			let element = $("#entities").append('<div class="row" style="flex-wrap: nowrap;"><div class="col col-md-11" style="padding-right: 0!important;"><input type="text" class="form-control entity" placeholder="Entity name" /></div><div class="col col-md-1 remove-entity" style="padding-left: 0!important;"> <a class="remove-entity"style="margin-top: 8px;color: red;"><i class="fa fa-minus-circle"style="margin-top: 8px;"></i></a></div></div></br>');
		}
		$(".entity").on('keypress', function (e) {
			if (e.keyCode == 13) {
				copyEntity();
			}
		})
		$(".add-entity").on('click', function () {
			copyEntity();
		})
		$(".remove-entity").on('click', function () {
			$(this).parent().parent().remove();
		})
		function generateInter(name, basePackageName) {
			let data = "package " + basePackageName + ".service.inter;\n" +
				"\nimport " + basePackageName + ".entity." + name + ";\n"
				+ "import org.springframework.stereotype.Service;\n" +
				"import java.util.List;\n"
			data += "@Service \n"
			data += "public interface " + name + "ServiceInter{\n"
			data += "List<" + name + "> findAll();\n"
			data += name + " findById(Integer id);\n"
			data += name + " save(" + name + " " + "v" + name + ");\n"
			data += "int deleteById(Integer id);\n}"
			download(name + "ServiceInter.java", data)
		}
		function generateImpl(name, basePackageName) {
			let data = "package " + basePackageName + ".service.impl;\n"
				+ "import " + basePackageName + ".entity." + name + ";\n"
				+ "import org.springframework.stereotype.Service;\n" +
				"import java.util.List;\n" + "import " + basePackageName + ".dao." + name + "DAOInter;\n"+
				"import " + basePackageName + ".service.inter." + name + "ServiceInter;\n"+"import org.springframework.beans.factory.annotation.Autowired;"
			data += "@Service \n"
			data += "public class " + name + "ServiceImpl implements " + name + "ServiceInter{\n\n"
			data += "@Autowired\n" + name + "DAOInter a"+name+"DaoInter;\n"
			data += "@Override\n" +
				"public List<" + name + "> findAll() {\n" +
				"return (List<"+name+">)a"+name+"DaoInter.findAll();\n}";
			data += "\n@Override\n" +
				"public " + name + " findById(Integer id) {\n" +
				"return a"+name+"DaoInter.findById(id).get();\n}\n";
			data += "@Override\n" +
				"public " + name + " save(" + name + " v" + name + ") {\n" +
				" return a"+name+"DaoInter.save(v"+name+");\n}\n"
			data += "@Override\n" +
				"public int deleteById(Integer id) {"
					+"a"+name+"DaoInter.deleteById(id);"+  "\n return 0;\n}\n}"
			download(name + "ServiceImpl.java", data)
		}
		function generateDAO(name, basePackageName) {
			let data = "package " + basePackageName + ".dao;\n"
			data += "import " + basePackageName + ".entity." + name + ";\n"
			data += "import org.springframework.data.repository.CrudRepository;\n" +
				"import javax.transaction.Transactional;\n"
			data += "@Transactional\n" +
				"public interface " + name + "DAOInter extends CrudRepository<" + name + ", Integer> {\n\n}"
			download(name + "DAOInter.java", data)
		}
	})


</script>

</html>