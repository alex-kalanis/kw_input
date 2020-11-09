
from kw_input.entries import IEntry
from kw_input.sources import ISource
from kw_input.parsers import Factory as ParserFactory
from kw_input.loaders import Factory as LoaderFactory


class Inputs:
    """
     * Base class for passing info from input
    """

    def __init__(self, source: ISource):
        self._entries = []
        self._source = source
        self._parser_factory = ParserFactory()
        self._loader_factory = LoaderFactory()

    def load_inputs(self, cli_args=None):
        self._entries = self._load_input(IEntry.SOURCE_GET, self._source.get()) \
            + self._load_input(IEntry.SOURCE_POST, self._source.post()) \
            + self._load_input(IEntry.SOURCE_CLI, cli_args) \
            + self._load_input(IEntry.SOURCE_SESSION, self._source.session()) \
            + self._load_input(IEntry.SOURCE_FILES, self._source.files()) \
            + self._load_input(IEntry.SOURCE_ENV, self._source.env()) \
            + self._load_input(IEntry.SOURCE_SERVER, self._source.server())

    def _load_input(self, source: str, input_array=None):
        if not input_array:
            return []
        parser = self._parser_factory.get_loader(source)
        loader = self._loader_factory.get_loader(source)
        return loader.load_vars(source, parser.parse_input(input_array))

    def get_basic(self):
        return self.get_in((
            IEntry.SOURCE_CLI,
            IEntry.SOURCE_GET,
            IEntry.SOURCE_POST,
        ))

    def get_system(self):
        return self.get_in((
            IEntry.SOURCE_SERVER,
            IEntry.SOURCE_ENV,
        ))

    def get_cli(self):
        return self.get_in(IEntry.SOURCE_CLI)

    def get_get(self):
        return self.get_in(IEntry.SOURCE_GET)

    def get_post(self):
        return self.get_in(IEntry.SOURCE_POST)

    def get_session(self):
        return self.get_in(IEntry.SOURCE_SESSION)

    def get_files(self):
        return self.get_in(IEntry.SOURCE_FILES)

    def get_server(self):
        return self.get_in(IEntry.SOURCE_SERVER)

    def get_env(self):
        return self.get_in(IEntry.SOURCE_ENV)

    def get_in(self, sources):
        for entry in self._entries:
            if entry.get_source() in sources:
                yield entry

    def into_key_object_array(self, entries):
        result = []
        for entry in entries:
            result.append((entry.get_key(), entry))
        return dict(result)
