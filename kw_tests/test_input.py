from kw_input.entries import IEntry
from kw_input.input import Inputs
from kw_input.sources import ISource
from kw_tests.common_class import CommonTestClass


class InputTest(CommonTestClass):

    def test_entry(self):

        source = MockSource()
        source.set_remotes(self.entry_dataset())

        input = Inputs(source)
        input.load_inputs(self.cli_dataset())

        assert 0 < len(self._iterator_to_array(input.get_cli()))
        assert 0 < len(self._iterator_to_array(input.get_get()))
        assert 1 > len(self._iterator_to_array(input.get_post()))
        assert 1 > len(self._iterator_to_array(input.get_session()))
        assert 1 > len(self._iterator_to_array(input.get_files()))
        assert 1 > len(self._iterator_to_array(input.get_server()))
        assert 1 > len(self._iterator_to_array(input.get_env()))
        assert 0 < len(self._iterator_to_array(input.get_basic()))
        assert 1 > len(self._iterator_to_array(input.get_system()))

        entries = input.into_key_object_array(input.get_get())
        assert entries

        assert 'foo' in entries.keys()
        entry = entries['foo']
        assert 'foo' == entry.get_key()
        assert 'val1' == entry.get_value()
        assert IEntry.SOURCE_GET == entry.get_source()

        assert 'bar' in entries.keys()
        entry = entries['bar']
        assert 'bar' == entry.get_key()
        assert 'bal1' == entry.get_value()[0]
        assert 'bal2' == entry.get_value()[1]

        assert 'baz' in entries.keys()
        entry = entries['baz']
        assert 'baz' == entry.get_key()
        assert True == entry.get_value()
        assert IEntry.SOURCE_GET == entry.get_source()

        assert 'aff' in entries.keys()
        entry = entries['aff']
        assert 'aff' == entry.get_key()
        assert 42 == entry.get_value()
        assert IEntry.SOURCE_GET == entry.get_source()

    def test_files(self):

        source = MockSource()
        source.set_remotes(self.entry_dataset(), None, self.file_dataset())

        input = Inputs(source)
        input.load_inputs()

        assert 1 > len(self._iterator_to_array(input.get_cli()))
        assert 0 < len(self._iterator_to_array(input.get_get()))
        assert 1 > len(self._iterator_to_array(input.get_post()))
        assert 1 > len(self._iterator_to_array(input.get_session()))
        assert 0 < len(self._iterator_to_array(input.get_files()))
        assert 1 > len(self._iterator_to_array(input.get_server()))
        assert 1 > len(self._iterator_to_array(input.get_env()))
        assert 0 < len(self._iterator_to_array(input.get_basic()))
        assert 1 > len(self._iterator_to_array(input.get_system()))

        entries = input.into_key_object_array(input.get_files())
        assert entries

        assert 'files' in entries.keys()
        entry = entries['files']
        assert 'files' == entry.get_key()
        assert 'facepalm.jpg' == entry.get_value()
        assert IEntry.SOURCE_FILES == entry.get_source()

        assert 'download[file1]' in entries.keys()
        entry = entries['download[file1]']
        assert 'download[file1]' == entry.get_key()
        assert 'MyFile.txt' == entry.get_value()
        assert IEntry.SOURCE_FILES == entry.get_source()

        assert 'download[file2]' in entries.keys()
        entry = entries['download[file2]']
        assert 'download[file2]' == entry.get_key()
        assert 'MyFile.jpg' == entry.get_value()
        assert IEntry.SOURCE_FILES == entry.get_source()


class MockSource(ISource):

    def __init__(self):
        self._mock_get = None
        self._mock_post = None
        self._mock_files = None
        self._mock_session = None

    def set_remotes(self, get, post=None, files=None, session=None):
        self._mock_get = get
        self._mock_post = post
        self._mock_files = files
        self._mock_session = session
        return self

    def get(self):
        return self._mock_get

    def post(self):
        return self._mock_post

    def files(self):
        return self._mock_files

    def session(self):
        return self._mock_session

    def server(self):
        return None

    def env(self):
        return None
